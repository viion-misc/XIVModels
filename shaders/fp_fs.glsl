precision highp float;

uniform mat4 uPMatrix;
uniform mat4 uMMatrix;
uniform mat4 uVMatrix;

uniform mat4 uInvMVMatrix;

uniform bool uDiffuse;
uniform bool uSpecular;
uniform bool uNormal;
uniform bool uTable;
uniform bool uMask;

uniform sampler2D uDiffuseTex;
uniform sampler2D uNormalTex;
uniform sampler2D uSpecularTex;
uniform sampler2D uTableTex;
uniform sampler2D uMaskTex;

varying mat4 vTSMatrix;

varying vec4 vVertexPosition;
varying vec4 vVertexNormal;
varying vec4 vTextureCoord;
varying vec4 vVertexColor;

void main() {
    vec4 normal_color = vec4(1.0, 1.0, 1.0, 1.0);
    if (uNormal) {
        normal_color = texture2D(uNormalTex, vTextureCoord.xy);

        // Alpha testing
        if (normal_color.b < 0.5) {
            discard;
        }
    }

    vec4 final_color = vec4(0.6, 0.6, 0.6, 1.0);
    vec4 diffuse_color = final_color;

    if (uDiffuse) {
        diffuse_color = texture2D(uDiffuseTex, vTextureCoord.xy);
    } else if (uMask) {
        diffuse_color = texture2D(uMaskTex, vTextureCoord.xy);
    }

    vec4 normal = vVertexNormal;

    if (uNormal) {
        vec4 normal_raw = texture2D(uNormalTex, vTextureCoord.xy);
        normal.xyz = normalize(((normal_raw * 2.0 - 1.0) * vTSMatrix).xyz);
        normal.a = normal_raw.a;
    }

    vec4 table_color = vec4(1.0, 1.0, 1.0, 1.0);
    vec4 table_specular = vec4(1.0, 1.0, 1.0, 20.0);
    vec4 table_unknown1 = vec4(0.0, 0.0, 0.0, 0.007813);
    vec4 table_unknown2 = vec4(16.0, 0.0, 0.0, 16.0);

    if (uTable && uNormal) {
        table_color = texture2D(uTableTex, vec2(0.125, normal.a));
        table_specular = texture2D(uTableTex, vec2(0.375, normal.a));
        table_unknown1 = texture2D(uTableTex, vec2(0.625, normal.a));
        table_unknown2 = texture2D(uTableTex, vec2(0.875, normal.a));

        diffuse_color = vec4(table_color.xyz * diffuse_color.xyz, 1.0);
    }

    if (!gl_FrontFacing) {
        normal = -normal;
    }

    final_color = diffuse_color * 0.3;

    vec3 screen_center = (uInvMVMatrix * vec4(0.0, 0.0, 0.0, 1.0)).xyz;
    vec3 eye_dir = normalize(vVertexPosition.xyz - screen_center);
    vec3 light_dir = normalize(-screen_center);
    float lambertTerm = dot(normal.xyz, -light_dir);
    if (lambertTerm > 0.0) {
        final_color += lambertTerm * diffuse_color;

        if (uSpecular) {
            vec4 specular_color = texture2D(uSpecularTex, vTextureCoord.xy);

            vec3 reflect_dir = reflect(light_dir, normal.xyz);
            float specular = pow( max(dot(reflect_dir, -eye_dir), 0.0), table_specular.a);
            final_color += vec4(1.0, 1.0, 1.0, 1.0) * specular_color.r * specular_color.a * specular;
        }
    }

    gl_FragColor = vec4(final_color.xyz, 1.0);
}
