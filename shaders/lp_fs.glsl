precision highp float;

uniform float uSize;

uniform sampler2D uInTex;

varying vec2 vPosition;

void main() {
    gl_FragColor = texture2D(uInTex, vPosition);
}
