precision highp float;

uniform mat4 uMMatrix;
uniform mat4 uVMatrix;
uniform mat4 uPMatrix;

attribute vec3 aVertexPosition;
attribute vec3 aVertexNormal;
attribute vec2 aTextureCoord;

varying vec3 vVertexPosition;
varying vec3 vVertexNormal;
varying vec2 vTextureCoord;

void main(void) {
    gl_Position = uPMatrix * uVMatrix * uMMatrix * vec4(aVertexPosition, 1.0);
    vTextureCoord = aTextureCoord;
    vVertexNormal = aVertexNormal;
    vVertexPosition = aVertexPosition;
}
