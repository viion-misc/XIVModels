precision highp float;

uniform mat4 uMMatrix;
uniform mat4 uVMatrix;
uniform mat4 uPMatrix;

attribute vec4 aVertexPosition;
attribute vec4 aVertexNormal;
attribute vec4 aTextureCoord;
attribute vec4 aVertexBinormal;
attribute vec4 aVertexColor;

varying mat4 vTSMatrix;

varying vec4 vVertexPosition;
varying vec4 vVertexNormal;
varying vec4 vTextureCoord;
varying vec4 vVertexColor;

void main(void) {
	vVertexPosition = aVertexPosition;
	vTextureCoord = aTextureCoord;
	vVertexNormal = vec4(normalize(aVertexNormal.xyz), aVertexNormal.a);
	vec4 vertexBinormal = (aVertexBinormal * 2.0 / 255.0) - 1.0;
	float binormalAlpha = vertexBinormal.a;
	vertexBinormal = normalize(vertexBinormal);
	vec3 vertexTangent = vertexBinormal.a * cross(vertexBinormal.xyz, vVertexNormal.xyz);
	vVertexColor = aVertexColor;

	vTSMatrix = mat4(
		vec4(vertexTangent.x, vertexBinormal.x, vVertexNormal.x, 0.0),
		vec4(vertexTangent.y, vertexBinormal.y, vVertexNormal.y, 0.0),
		vec4(vertexTangent.z, vertexBinormal.z, vVertexNormal.z, 0.0),
        vec4(0.0, 0.0, 0.0, 1.0));

    gl_Position = uPMatrix * uVMatrix * uMMatrix * aVertexPosition;
}
