#include <Process.h>

#define SWITCH_PIN 2
#define LED_PIN 13

bool alertSent;

void setup() {
  
  Bridge.begin();
  
  Serial.begin(9600);
  while (!Serial);
  
  pinMode(SWITCH_PIN, INPUT);
  pinMode(LED_PIN, OUTPUT);
  
  digitalWrite(LED_PIN, LOW);
  alertSent = false;
  Serial.println("Door monitor started!");
}
  
void loop() {
  
  // check if the switch is clicked
  if(digitalRead(SWITCH_PIN) == HIGH) {
  
    // debounce: read again the PIN after 50ms
    delay(50);
    if(digitalRead(SWITCH_PIN) == HIGH) {
      
      // if an alert hasn't been already sent
      if(!alertSent) {
        
        digitalWrite(LED_PIN, HIGH);
        Serial.print("Door opened! Sending SMS...");
        sendAlert();
        Serial.println(" sent!");
        alertSent = true;
      }
    }
  } else {
    
    // reset the LED and variable status
    digitalWrite(LED_PIN, LOW);
    alertSent = false;
  }
}

void sendAlert() {

  Process p;            
  p.begin("php-cli");      
  p.addParameter("/root/sendSMSAlert.php"); 
  p.run();
}
