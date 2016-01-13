/* @pjs transparent=true; */
/* @pjs globalKeyEvents="true"; */
/* @pjs crisp=true; */

// Modern Art Clock
// O-R-G 

void setup() {
  size(200, 200);
  stroke(0);
  smooth();
}

void draw() {
  background(255);
  // fill(240);
  noFill();
  stroke(0);
  strokeWeight(1);
  // noStroke();
  // Angles for sin() and cos() start at 3 o'clock;
  // subtract HALF_PI to make them start at the top
  ellipse(100, 100, 160, 160);
  float s = map(second(), 0, 60, 0, TWO_PI) - HALF_PI;
  float m = map(minute(), 0, 60, 0, TWO_PI) - HALF_PI;
  float h = map(hour() % 12, 0, 12, 0, TWO_PI) - HALF_PI;
  stroke(0);
  strokeWeight(1);
  line(100, 100, cos(s) * 72 + 100, sin(s) * 72 + 100);
  strokeWeight(2);
  line(100, 100, cos(m) * 70 + 100, sin(m) * 70 + 100);
  strokeWeight(2);
  line(100, 100, cos(h) * 44 + 100, sin(h) * 44 + 100);
}
