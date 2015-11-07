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
varying vec4 vVertexBinormal;
varying vec4 vTextureCoord;
varying vec4 vVertexColor;

void main() {
    //gl_FragColor = vec4(texture2D(uDiffuseTex, vTextureCoord.xy).xyz, 1.0);
    //gl_FragColor = vec4((vec4(1.0, 1.0, 1.0, 1.0) * texture2D(uNormalTex, vTextureCoord.xy).a).xyz, 1.0);
    //vec4((vec4(1.0, 0.0, 0.0, 1.0) * (1.0 - texture2D(uSpecularTex, vTextureCoord.xy).a)).xyz, 1.0);

    /*if (vTextureCoord.z < -0.9) {
        gl_FragColor= vec4(1.0, 1.0, 1.0, 1.0);
    } else if (vTextureCoord.z < 0.1) {
        gl_FragColor= vec4(0.0, 0.0, 0.0, 1.0);
    }
    else {
        gl_FragColor= vec4(1.0, 0.0, 0.0, 1.0);
    }*/

    /*if (vTextureCoord.z > 1.0 || vTextureCoord.z < 0.0 || vTextureCoord.w > 1.0 || vTextureCoord.w < 0.0)
    {
        gl_FragColor = vec4(0.0, 0.0, 1.0, 1.0);
    }
    else {
        gl_FragColor = vec4(vTextureCoord.zw, 0.0, 1.0);
    }*/
    //gl_FragColor = vTextureCoord;
    //gl_FragColor = vec4(texture2D(uSpecularTex, vTextureCoord.xy).a * vec3(1.0, 1.0, 1.0), 1.0);
    //gl_FragColor = vec4(texture2D(uDiffuseTex, vTextureCoord.xy).xyz, 1.0);

    gl_FragColor = vec4(texture2D(uTableTex, vec2(0.125, texture2D(uNormalTex, vTextureCoord.xy).a)).rgb * texture2D(uMaskTex, vTextureCoord.xy).rgb, 1.0);

    //gl_FragColor = vec4(vec3(1.0, 1.0, 1.0) * texture2D(uNormalTex, vTextureCoord.xy).a, 1.0);
    //gl_FragColor = texture2D(uMaskTex, vTextureCoord.xy);
    //gl_FragColor = texture2D(uTableTex, vec2(0.0, 4.0/15.0));
/*
    if (texture2D(uSpecularTex, vTextureCoord.xy).a < 15.0/15.0 + 0.01)
    {
        gl_FragColor = vec4(1.0, 1.0, 1.0, 1.0);
    } else {
        gl_FragColor = vec4(0.0, 0.0, 0.0, 1.0);
    }
*/

    //gl_FragColor = vec4(texture2D(uSpecularTex, vTextureCoord.xy).a * vec3(1.0, 1.0, 1.0), 1.0);

    //gl_FragColor = vec4(texture2D(uNormalTex, vTextureCoord.xy).a * vec3(1.0, 1.0, 1.0), 1.0);

    //gl_FragColor = vec4(texture2D(uNormalTex, vTextureCoord.xy).a * vec3(1.0, 1.0, 1.0), 1.0);
    //gl_FragColor = vec4(vVertexColor.xyz * vVertexColor.xyz, vVertexColor.a);


    //gl_FragColor = vec4(vec3(1.0, 0.0, 0.0) * texture2D(uNormalTex, vTextureCoord.xy).a + vec3(0.0, 1.0, 0.0) * texture2D(uDiffuseTex, vTextureCoord.xy).a
    //    + vec3(0.0, 0.0, 1.0) * texture2D(uSpecularTex, vTextureCoord.xy).a, 1.0);
}
