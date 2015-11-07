precision highp float;

uniform float uSize;

uniform sampler2D uInTex;

varying vec2 vPosition;

vec4 applyGlow(vec4 colour, vec2 fragCoord, sampler2D tex)
{
  int samples = 5; // pixels per axis; higher = bigger glow, worse performance
  float quality = 2.5; // lower = smaller glow, better quality

  vec4 source = texture2D(tex, fragCoord);
  vec4 sum = vec4(0);
  int diff = (samples - 1) / 2;
  vec2 sizeFactor = vec2(1) / uSize * quality;

  for (int x = -diff; x <= diff; x++)
  {
    for (int y = -diff; y <= diff; y++)
    {
      vec2 offset = vec2(x, y) * sizeFactor;
      sum += texture2D(tex, fragCoord + offset);
    }
  }

  return ((sum * (1.0 / float(samples * samples))) + source) * colour;
}

void main() {
    gl_FragColor = applyGlow(texture2D(uInTex, vPosition), vPosition, uInTex);
}
