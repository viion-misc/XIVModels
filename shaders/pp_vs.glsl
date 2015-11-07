precision highp float;

attribute vec2 aPosition;

varying vec2 vPosition;

void main(void) {
    vPosition = aPosition * 0.5 + 0.5;
    gl_Position = vec4(aPosition, 0.0, 1.0);
}