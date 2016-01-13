/* @pjs transparent=true; */
/* @pjs crisp=true; */

// Modern Art Clock
// O-R-G 

float powerCounter = 0;
int resonatorCounter = 0;
int displayCounter = 0;
int reverseSwitch = 1;


void setup() {
  size(200, 200);
  stroke(0);
  smooth();
  background(255);
}

void draw() {
  background(255); 

  displayCounter++;
  // powerCounter++; 
  
  if ( displayCounter % 14 == 0 ) {
     resonatorCounter++;  
  }
  
  if ( displayCounter % 300 == 0 ) {
      // reverseSwitch = reverseSwitch * -1;
  }
  
  powerCounter = ( ( ( displayCounter % 300 ) / 3 ) * reverseSwitch ) + 2.0;

  noFill();
  stroke(0);
  strokeWeight(1);
  // noStroke();
  // Angles for sin() and cos() start at 3 o'clock;
  // subtract HALF_PI to make them start at the top
  ellipse(100, 100, 160, 160);
  // ellipse(300, 100, 160, 160);
  // ellipse(500, 100, 160, 160);
  // ellipse(300, 100, 160, 160);
  // float s = map(second(), 0, 60, 0, TWO_PI) - HALF_PI;
  // float m = map(minute(), 0, 60, 0, TWO_PI) - HALF_PI;
  float s = map(displayCounter % 60, 0, 60, 0, TWO_PI) - HALF_PI;
  float m = map((resonatorCounter % 10) + 30, 0, 60, 0, TWO_PI) - HALF_PI;
  float h = map(hour() % 12, 0, 12, 0, TWO_PI) - HALF_PI;
  stroke(0);
  strokeWeight(1);
  
  // Display
  // line(100, 100, -cos(s) * 72 + 100, sin(s) * 72 + 100);
  ellipse(cos(s) * 72 + 100, sin(s) * 72 + 100, 10, 10);
  // ellipse(cos(h) * 72 + 100, sin(h) * 72 + 100, 5, 5);
  // line((cos(h) * 72 + 100) - 10, sin(h) * 72 + 100, (cos(h) * 72 + 100), sin(h) * 72 + 100);
  line((cos(h) * 72 + 100) + 6, (sin(h) * 72 + 100) - 6, (cos(h) * 72 + 100), sin(h) * 72 + 100);
  line((cos(h) * 72 + 100), (sin(h) * 72 + 100) - 6, (cos(h) * 72 + 100) + 6, sin(h) * 72 + 100);
  // Resonator
  // line(200, 20, cos(m) * 68 + 200, sin(m) * 68 + 100);

    
  // line(300, 100, cos(h) * 44 + 300, sin(h) * 44 + 100);
  
  
  // Power
  translate(100,100);
  strokeWeight(1);
  beginShape();
  for(int i=0;i<60;i++) {
      // curveVertex((i*1.2)*sin(i/2.0),(i*1.2)*cos(i/2.0));
      curveVertex((i*1.2)*sin(i/powerCounter),(i*1.2)*cos(i/powerCounter));
  }
  endShape(); 
  
}
