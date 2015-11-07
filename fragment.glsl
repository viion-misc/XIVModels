#extension GL_OES_standard_derivatives : enable

precision highp float;

uniform bool uTexture;
uniform bool uDiffuse;
uniform bool uSpecular;
uniform bool uNormal;

uniform mat4 uMMatrix;
uniform mat4 uVMatrix;

uniform sampler2D uDiffuseTex;
uniform sampler2D uNormalTex;
uniform sampler2D uSpecularTex;

varying vec2 vTextureCoord;
varying vec3 vVertexNormal;
varying vec3 vVertexPosition;

mat3 cotangent_frame(vec3 N, vec3 p, vec2 uv)
{
    vec3 dp1 = dFdx( p );
    vec3 dp2 = dFdy( p );
    vec2 duv1 = dFdx( uv );
    vec2 duv2 = dFdy( uv );

    vec3 dp2perp = cross( dp2, N );
    vec3 dp1perp = cross( N, dp1 );
    vec3 T = dp2perp * duv1.x + dp1perp * duv2.x;
    vec3 B = dp2perp * duv1.y + dp1perp * duv2.y;

    float invmax = inversesqrt( max( dot(T,T), dot(B,B) ) );
    return mat3( T * invmax, B * invmax, N );
}

vec3 perturb_normal( vec3 N, vec3 V, vec2 texcoord )
{
    vec3 map = texture2D(uNormalTex, texcoord).xyz;
    map = map * 255./127. - 128./127.;
    mat3 TBN = cotangent_frame(N, -V, texcoord);
    return normalize(TBN * map);
}

void main()
{
    vec4 normal_mv = uVMatrix * uMMatrix * vec4(vVertexNormal, 1.0);
    vec4 pos_mv = uVMatrix * uMMatrix * vec4(vVertexPosition, 1.0);
    vec4 eye_dir_mv = -pos_mv;

    vec4 lights_pos[3];
    lights_pos[0] = vec4(5.0, 0.0, 0.0, 1.0);
    lights_pos[1] = vec4(0.0, 5.0, 0.0, 1.0);
    lights_pos[2] = vec4(0.0, 0.0, 5.0, 1.0);

    vec2 uv = vTextureCoord.xy;

    vec3 N = normalize(normal_mv.xyz);
    vec3 V = normalize(eye_dir_mv.xyz);
    vec3 PN = perturb_normal(N, V, uv);

    vec4 diffuse_color = texture2D(uDiffuseTex, uv);
    vec4 final_color = vec4(0.2, 0.15, 0.15, 1.0) * diffuse_color;

    for (int index = 0; index < 3; index++)
    {
        vec4 light_dir_mv = uVMatrix * uMMatrix * lights_pos[index] - pos_mv;
        vec3 L = normalize(light_dir_mv.xyz);

        float lambertTerm = dot(PN, L);
        if (lambertTerm > 0.0)
        {
            final_color += lambertTerm * diffuse_color;

            vec3 E = normalize(eye_dir_mv.xyz);
            vec3 R = reflect(-L, PN);
            float specular = pow( max(dot(R, E), 0.0), 10.0);

            vec4 specular_color = texture2D(uSpecularTex, uv);

            final_color += specular_color * specular;
        }
    }

    gl_FragColor = vec4(final_color.rgb, 1.0);
}
