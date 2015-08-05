// HL1606strip is an adaptation of LEDstrip from  http://code.google.com/p/ledstrip/
#include "HL1606strip.h"

#include <Adafruit_NeoPixel.h>

#define PIXEL_PIN    A4    // Digital IO pin connected to the NeoPixels.

#define PIXEL_COUNT 16

#define COIN_PIN 13 //FIX DIS
#define BUTTON_PIN 11 //FIX DIS

// use -any- 3 pins!
#define STRIP_D 9
#define STRIP_C 6
#define STRIP_L 5


#define stepPin         7
#define dirPin          10
#define enablePin       4
#define minPin          A3
#define servo           A5

#define MicroStep3Pin -1
#define MicroStep2Pin -1
#define MicroStep1Pin -1

#define resetPin -1
#define sleepPin -1


#include <Servo.h> 
 
Servo myservo;  // create servo object to control a servo 


// Pin S is not really used in this demo since it doesnt use the built in PWM fade
// The last argument is the number of LEDs in the strip. Each chip has 2 LEDs, and the number
// of chips/LEDs per meter varies so make sure to count them! if you have the wrong number
// the strip will act a little strangely, with the end pixels not showing up the way you like


#define numMsInOneSec 1000
#define numMicroSecInOneMs 1000
#define stepPulseWidthInMicroSec 2
#define setupTimeInMicroSec 5


#define inputBufferSize 128

/*
    USEFULL VARS
*/
HL1606strip strip = HL1606strip(STRIP_D, STRIP_L, STRIP_C, 34);
Adafruit_NeoPixel neostrip = Adafruit_NeoPixel(PIXEL_COUNT, PIXEL_PIN, NEO_GRB + NEO_KHZ800);

const int color1= GREEN;
const int color2= YELLOW;

int neoj=0;
int supercarIndex=0;
boolean supercarDirection=0;//Direction of movement
int strip1=0;

//  position array =   0      1     2      3      4      5      6      7      8     9   
unsigned int pos[] = {0000, 6300, 12600, 18900, 25200, 31500, 37800, 44100, 50400, 56700};
unsigned int actualpos=0;

int movementDelay=150;  //low=fast
const int accelerationTreshold=1500;  //acceleration

int servopos = 0;    // variable to store the servo position 
int servomin = 30;    // variable to store the servo position 
int servomax = 90;    // variable to store the servo position 

bool hasLights=false;

int timeToSpill = 3500;    
int timeToRefill = 2000;    

/*
  END USEFULL VARS
*/

boolean currentDirection = false;

void setup() 
{
  // We set the enable pin to be an output 
  pinMode(enablePin, OUTPUT); // then we set it HIGH so that the board is disabled until we 
  pinMode(stepPin, OUTPUT); 
  pinMode(dirPin, OUTPUT);
  pinMode(resetPin, OUTPUT);
  pinMode(sleepPin, OUTPUT);
  
  pinMode(COIN_PIN, INPUT);
  digitalWrite(COIN_PIN, HIGH);
  pinMode(BUTTON_PIN, INPUT);
  digitalWrite(BUTTON_PIN, HIGH);
  
  pinMode(minPin, INPUT);
  digitalWrite(minPin, HIGH);  
  
  pinMode(MicroStep1Pin, OUTPUT);
  pinMode(MicroStep2Pin, OUTPUT);
  pinMode(MicroStep3Pin, OUTPUT);
  
  digitalWrite(resetPin, HIGH);  
  digitalWrite(sleepPin, HIGH);
  
    // get into a known state. 
  enableStepper(false);
  // we set the direction pin in an arbitrary direction.
  setCurrentDirection(false);
  //setFullStep();
  
  myservo.attach(servo);  // attaches the servo on pin 9 to the servo object 

  neostrip.begin();
  neostrip.show(); // Initialize all pixels to 'off'


  Serial.begin(9600); 
  Serial.println("Starting stepper dev tester.");
  
  enableStepper(true);
 // we set the direction pin in an arbitrary direction. 
  setCurrentDirection(true);
  servores();
  delay (500);
  
  myservo.detach();
}

void loop(){
  if (Serial.available() > 0) {
    String message="";
    while(Serial.available()){ 
      message+= Serial.read();
    }
    parseString(message);
  }
  if(hasLights)idleLights();
}
/*
  COMMUNICATION METHODS
*/
void parseString(String message){
  if(message.startsWith("!") && message.endsWith("\n")){
     if(message == "!GoHome\n"){
       goHome();
       success();
     }else if(message.startsWith("!NewDrink")){
         int firstIndex = message.indexOf('|');
         int secondIndex = message.indexOf('|', firstIndex+1);
         int thirdIndex = message.indexOf('|',secondIndex+1);
         int start =(message.substring(firstIndex+1,secondIndex)).toInt();
         int time=(message.substring(secondIndex+1,thirdIndex)).toInt();
         hasLights= (message.substring(thirdIndex+1)).toInt() == 1;
         goHome();
         if(waitForActivation(start,time)){
            success(); 
         }else{
           timeout();
         }
     } else {
         int index = message.indexOf('|');
         int pos = (message.substring(1,index)).toInt();
         int times = (message.substring(index+1)).toInt();
         reachBottle(pos);
         spill(times);
         if(pos==0 && times==0 && hasLights)rainbowParty(30);
         success();
     }
  }  
}

void success(){
  Serial.println("1");
}
void timeout(){
  Serial.println("2");
}

/*
  END COMMUNICATION METHODS
*/

bool waitForActivation(int mode, int time){
  unsigned long start= millis();
  unsigned long totalMs = time*1000;
  unsigned long elapsed =0;
  ledSet(color2);
  strip.writeStrip();
  while(elapsed<totalMs){
     switch(mode){ 
        case 0:
          break;
        case 1:
          if(digitalRead(COIN_PIN)==LOW){
            return true;  
          }
          break;
        case 2:
          if(digitalRead(BUTTON_PIN)==LOW){
            return true;  
          }
          break;
     }
     elapsed=(millis()-start);
     if(hasLights)ledCountdown(elapsed,totalMs);
  }
  return (mode==0); //If we're here it means the loop timed out, if mode is 0 (auto) this is a good thing, so we return true, otherwise it's an user timeout, so we return false
}


/*
  NAVIGATION METHODS
*/
void reachBottle(int bottle)
{
  unsigned int startPos=actualpos;
  int deltaPos=0;

  if(pos[bottle]==actualpos){
    return;
  }
  else if (pos[bottle]>actualpos){
    gofw();
    deltaPos=1;
    Serial.println("Go FW ");
  }
  else {
    gobw(); 
    deltaPos=-1; 
    Serial.println("Go BW ");
  } 
  enableStepper(true);
  delay (50);
  for(; actualpos!=pos[bottle]; actualpos+=deltaPos) 
    { 
      int accelerationDelay=0;
      long timetoexecute=micros();
      int elerationDelay=0;
      int moved = abs(actualpos-startPos);
      int missing = abs(actualpos-pos[bottle]);
      if(moved<accelerationTreshold){ //if we've moved less than accelerationTreshold
        accelerationDelay=accelerationTreshold-moved; //add a delay
      }else if(missing < accelerationTreshold){//if we've almost arrived(the distance between the two is less than accelerationTreshold)
        accelerationDelay=accelerationTreshold-missing;
      }
      takeSingleStep();//Move      
      delayMicroseconds(movementDelay+accelerationDelay);//Sleep 
      if(checkForHome()){//If we've got home
        break;  
      }
    }
    if(hasLights)ledsBlink();
    enableStepper(false);
}

void goHome(){
  gobw();
  while(checkForHome()){
    takeSingleStep();  
    delayMicroseconds(movementDelay);
  }
}

bool checkForHome(){
        if ((digitalRead(minPin)==1)){
        Serial.println("Home"); 
        enableStepper(false);
        actualpos=0;    // reset position
        return true;
      }
      return false;
}
/*
  END OF NAVIGATION METHODS
*/

/*
  LIGHT METHODS
*/

void idleLights(){
  neoj++;
    if (neoj>255) neoj=0;   
      for(int i=0; i<neostrip.numPixels(); i++) {
        neostrip.setPixelColor(i, Wheel((i+neoj) & 255));
      }
    neostrip.show();
    delay(20);
    if (supercarDirection==true){
    supercarIndex++;
    if (supercarIndex>strip.numLEDs()-1) 
    {
      supercarDirection=false;
      strip1++;
      if (strip1>6) strip1=1;
    }
  }
  else {
    supercarIndex--;    
    if (supercarIndex<1) {
      supercarDirection=true;  
      strip1++;
      if (strip1>6) strip1=1;
    }
  }
  strip.setLEDcolor(supercarIndex, strip1);
  strip.writeStrip();
}

// scroll a rainbow!
void rainbowParty(uint8_t wait) {
  uint8_t i, j, t;
  for (int t=0; t < 256; t=t+50) {     // cycle all 256 colors in the wheel
    for (int q=0; q < 3; q++) {
        for (int i=0; i < neostrip.numPixels(); i=i+3) {
          neostrip.setPixelColor(i+q, Wheel( (i+t) % 255));    //turn every third pixel on
        }
        neostrip.show();
          for (i=0; i< strip.numLEDs(); i+=6) {
            // initialize strip with 'rainbow' of colors
            strip.setLEDcolor(i, RED);
            strip.setLEDcolor(i+1, YELLOW);
            strip.setLEDcolor(i+2, GREEN);
            strip.setLEDcolor(i+3, TEAL);
            strip.setLEDcolor(i+4, BLUE);
            strip.setLEDcolor(i+5, VIOLET);
         
          }
          strip.writeStrip(); 
            for (j=0; j < strip.numLEDs(); j++) {
              // now set every LED to the *next* LED color (cycling)
              uint8_t savedcolor = strip.getLEDcolor(0);
              for (i=1; i < strip.numLEDs(); i++) {
                strip.setLEDcolor(i-1, strip.getLEDcolor(i));  // move the color back one.
              }
              // cycle the first LED back to the last one
              strip.setLEDcolor(strip.numLEDs()-1, savedcolor);
              strip.writeStrip();
              delay(25);
            }
        for (int i=0; i < neostrip.numPixels(); i=i+3) {
          neostrip.setPixelColor(i+q, 0);        //turn every third pixel off
        }
    }
  }
}

// Input a value 0 to 255 to get a color value.
// The colours are a transition r - g - b - back to r.
uint32_t Wheel(byte WheelPos) {
  WheelPos = 255 - WheelPos;
  if(WheelPos < 85) {
   return neostrip.Color(255 - WheelPos * 3, 0, WheelPos * 3);
  } else if(WheelPos < 170) {
    WheelPos -= 85;
   return neostrip.Color(0, WheelPos * 3, 255 - WheelPos * 3);
  } else {
   WheelPos -= 170;
   return neostrip.Color(WheelPos * 3, 255 - WheelPos * 3, 0);
  }
}

void ledCountdown(int elapsed, int total){
  int left=total-elapsed;
  int led = ceil((left/total)*strip.numLEDs())-1;
  if(led>0){
    strip.setLEDcolor(led, color1);
    strip.writeStrip();
    neostrip.setPixelColor(led, neostrip.Color(0,0,200)); // Moderately bright green color.
    neostrip.show();    
    delay(floor(total/strip.numLEDs()));
  }
}

void ledSet(int color){
  for(int i=0;i<strip.numLEDs();i++){
    strip.setLEDcolor(i, color);
  }
}

void ledsBlink(){
  for (int i=0;i<3;i++){
        ledSet(color2);
        strip.writeStrip();
        delay(200);
        ledSet(color1);
        strip.writeStrip();
        delay(200);
      }
}
/*
  EDND OF LIGHT METHODS*/

/*
  SERVO METHODS
*/
  void servoup()
  {
    for(servopos = servomin; servopos <= servomax; servopos += 1) 
    {                                  
      myservo.write(servopos);              
      delay(4);           
    }  
  }

void servodown()
{  
  for(servopos = servomax; servopos>=servomin; servopos-=1)
  {                                
    myservo.write(servopos);              
    delay(4);                       
  } 
}
void servores()
{  
  myservo.write(servomin);
}
void spill(int times){
  for ( int i = 0; i<times ; i++){
    servoup();
    delay(timeToSpill);
    servodown();
    if(i!=times-1) delay (timeToRefill);
  }  
}
/*
  END OF SERVO METHODS
*/


/*
  MOVEMENT METHODS (LOW LEVEL)
*/
void setCurrentDirection(boolean dir)
{
  if(dir == false)
  {
      digitalWrite(dirPin, LOW);
  } else {
      digitalWrite(dirPin, HIGH);
  }
  currentDirection = dir;
  delayMicroseconds(setupTimeInMicroSec);
}

void reverseDirection()
{
  setCurrentDirection(!currentDirection);
}

void gofw()
{
  setCurrentDirection(1);
}

void gobw()
{
  setCurrentDirection(0);
}


void enableStepper(int isEnabled)
{
  if(isEnabled)
  {
      digitalWrite(enablePin, LOW); // enable HIGH = stepper driver OFF
  } else {
      digitalWrite(enablePin, HIGH); // enable HIGH = stepper driver OFF
  }
  // wait a few microseconds for the enable to take effect 
  // (That isn't in the spec sheet I just added it for sanity.) 
  delayMicroseconds(2);
}

void takeSingleStep()
{
    digitalWrite(stepPin, LOW);
    delayMicroseconds(stepPulseWidthInMicroSec); 
    digitalWrite(stepPin, HIGH); 
    delayMicroseconds(stepPulseWidthInMicroSec); 
    digitalWrite(stepPin, LOW);
}
/*
  END OF MOVEMENT METHODS
*/

