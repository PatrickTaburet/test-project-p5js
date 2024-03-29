
// --------------------- Random walkers --------------------------
//--- - - - - - - - -   - - - - --- -- -- - -

//Catch data from DB:
let dataScene;
function preload() {
  const dataSceneJson = document.getElementById('dataScene').dataset.scene;
  if (dataSceneJson) {
    dataScene = JSON.parse(dataSceneJson);
  }  else {
    console.log("No scene data found");
  }
}
// console.log(dataScene.color);
// line walker

let cell = 1;
let walkers = [];

let lineSlider;
let colorSlider;
let weightSlider;
function setup() {

  createCanvas(windowWidth, windowHeight);
  colorMode(HSB);
  background(0,0,0);
  let defaultValueLine = dataScene ? dataScene.numLine : 100;
  let defaultValueColor = dataScene ? dataScene.color : 5;
  let defaultValueWeight = dataScene ? dataScene.weight : 8;
  console.log(defaultValueLine);
  lineSlider = createSlider(0, 100, defaultValueLine, 1).position(10, 10).size(80);
  colorSlider = createSlider(0, 360, defaultValueColor, 1).position(10, 30).size(80);
  weightSlider = createSlider(0, 10, defaultValueWeight, 1).position(10, 50).size(80);

  // Create un first walker serie in the center when app is open

  // for (let i = 0; i < 30; i++) {
  //   walkers.push(new Walker(width/2, height/2));
  //   walkers.forEach((walker)=> (walker.draw()));;
  // }
}

function draw(){
// console.log((color(150,100, 100, 52).levels));

  walkers.forEach(walker => {
    if (!walker.isOut()) {
      walker.velocity();
      walker.move();
      walker.draw();
      // console.log(walker.velocityX)
      // console.log (walker.color.levels)
      // console.log (uiBrightness.getValue())
    }
    
  });
}

class Walker {
  constructor(x, y) {
    this.x = x;
    this.y = y;
    this.px = x;
    this.py = y;
    this.velocityX = random(-uiVelocity.getValue(), uiVelocity.getValue());
    this.velocityY = random(-uiVelocity.getValue(),uiVelocity.getValue());
    this.color = color(random(colorSlider.value(), (colorSlider.value() + 200)), uiSaturation.getValue(), uiBrightness.getValue(), uiOpacity.getValue());
    this.draw();
  }
  velocity () {
    this.velocityX += map(noise(this.x * 0.005, this.y * 0.005, millis() * 0.001), 0, 1, -1, 1);
    this.velocityY += map(noise(this.y * 0.005, this.x * 0.005, millis() * 0.001), 0, 1, -1, 1);
  }
  isOut () {
    return(this.x < 0 || this.x > width || this.y < 0 || this.y > height);
  }
  move () {
    this.x += this.velocityX;
    this.y += this.velocityY;
  }
  draw () {
    line(this.x, this.y, this.px, this.py);
    this.px = this.x;
    this.py = this.y;
    noFill();
    noiseDetail(uiNoiseOctave.getValue(), uiNoiseFalloff.getValue());
    stroke(random(colorSlider.value(), (colorSlider.value() + 200)), uiSaturation.getValue(), uiBrightness.getValue(), uiOpacity.getValue());
    strokeCap(SQUARE);
    blendMode(SCREEN);
    // smooth();
    strokeWeight(weightSlider.value());
  }
  
}
function mouseClicked () {
    //walkers = []; -> uncomment to set only one walker for one click and stop the others walkers moves
  noiseSeed(random(50));
  for (let i = 0; i < lineSlider.value(); i++){
    walkers.push(new Walker(mouseX, mouseY));

  }
  
}
function reset () {
  resizeCanvas(windowWidth, windowHeight);
  walkers = [];
  clear();
  background(0,0,0);
}

// GUI interface : 


let walkersProps = {
  'Color' : 110,
  'Saturation' : 90,
  'Brightness' : 70,
  'Opacity' : 70,
  'Weight' : 3,
  'Amount' : window.innerWidth < 600 ? 400 : 1000,
  'Random' : 0.2,
  'Number of lines' : dataScene ? dataScene.numLine : 5,
  'Velocity' : 5,
  'noiseFalloff' : 0.5,
  'noiseOctave' : 4
};


let props = walkersProps;
let gui = new dat.GUI();
// Folders
let walkersFolder = gui.addFolder("Walkers");
let colorFolder = walkersFolder.addFolder("Colors");
let styleFolder = walkersFolder.addFolder("Style");
let moveFolder = walkersFolder.addFolder("Move");
// Props by folders
// let uiColor = colorFolder.add(props, 'Color', 0, 360, 10);
let uiSaturation = colorFolder.add(props, 'Saturation', 0, 100, 5);
let uiBrightness = colorFolder.add(props, 'Brightness', 0, 100, 5);
let uiOpacity = colorFolder.add(props, 'Opacity', 0, 1, 0.01);

// let uiWeight = styleFolder.add(props, 'Weight', 0, 10, 0.5);
// let lineNumber = styleFolder.add(props, 'Number of lines', 0, 100, 1);

let uiVelocity = moveFolder.add(props, 'Velocity', 0, 15, 0.1);
let uiNoiseOctave = moveFolder.add(props, 'noiseOctave', 0, 10, 1);
let uiNoiseFalloff = moveFolder.add(props, 'noiseFalloff', 0, 1, 0.05);
// let lineNumber = moveFolder.add(props, 'Number of lines', 0, 100, 1);
// gui.addColor(props, "Color"); -> Color picker pannel


// uiColor.onChange(reset);
uiSaturation.onChange(reset);
uiBrightness.onChange(reset);
uiOpacity.onChange(reset);
// uiWeight.onChange(reset);
// lineNumber.onChange(reset);
uiNoiseOctave.onChange(reset);
uiNoiseFalloff.onChange(reset);

var obj = { "Save data":function(){ sendData() }};

gui.add(obj,'Save data');

// Send to backend :



function sendData(){
  let color = colorSlider.value()
  let weight = weightSlider.value()
  let numLine = lineSlider.value()
    // let color = uiColor.getValue()
    // let weight = uiWeight.getValue()
    // let numLine = lineNumber.getValue()
    
   

    // Capture image of the canva

    // Capture l'image du canva dans un format base64
    const myCanvas = document.getElementById("defaultCanvas0");
    const imageBase64 = myCanvas.toDataURL();
    // const imageBase64 = canvas.elt.toDataURL();

    // Créez une nouvelle image à partir de l'URL base64
    const image = new Image();
    image.src = imageBase64;

    // Lorsque l'image est chargée, envoyez-la au serveur
    image.onload = function() {
      const formData = new FormData(); // or new URLSearchParams()
      formData.append('color', color);
      formData.append('weight', weight);
      formData.append('numLine', numLine);
      formData.append('file', image.src);

      // saveCanvas();
      fetch('/sendData', {
          method: 'POST',
          body: formData
      })
      .then(response => {
          if (!response.ok) {
              throw new Error('Network response was not ok');
          }
          return response.text();
      })
      .then(data => {
          console.log('Data sent successfully:', data);
      // Redirection vers la page 'gallery'
          // window.location.href = '/gallery';
      })
      .catch(error => {
          console.error('There was a problem sending the data:', error);
      });
    };
}

// function capture() {
//   // Capture l'image du canva dans un format base64
//   const imageBase64 = canvas.elt.toDataURL();

//   // Créez une nouvelle image à partir de l'URL base64
//   const image = new Image();
//   image.src = imageBase64;

//   // Lorsque l'image est chargée, envoyez-la au serveur
//   image.onload = function() {
//     // Création d'une nouvelle FormData
//     const formData = new FormData();
//     // Ajoutez l'image à la FormData
//     formData.append('file', image.src);

//     // Envoi de la requête HTTP POST pour uploader l'image
//     fetch('/upload', {
//       method: 'POST',
//       body: formData,
//     })
//     .then(function(response) {
//       console.log('Image uploaded successfully', response);
//     })
//     .catch(function(error) {
//       console.error('Error uploading image:', error);
//     });
//   }
// }

// Appelez la fonction capture() lorsque vous cliquez sur un bouton
// button.mousePressed(capture);