<html>
   <head>
      <title>Physics simulation Demo</title>
   </head>
   <body onload="init();" style="text-align: center;background: rgb(18, 18, 18);">
      <div id="problem"  style="color: rgb(166, 117, 163);width: 100%;">
         <div style="width: 640;margin: 30 auto;text-align: left;font-family: Courier, monospace;font-size: large;">
         <p> A cube is shot into the air. It needs to reach a height of 90m at the top of its flight. 
            What does its initial velocity have to be to do this?  </p>
         <label>Your answer: </label>
         <input id='answer' type='text' style="background-color: black; color: cyan;"></input>
         <label>m/s</label>
         </div
      </div>
      <canvas id="canvas" width="640" height="480" style="background-color:#333333;" ></canvas>
   </body>
   <?php ?>
   <script type="text/javascript" src="Box2dWeb-2.1.a.3.min.js"></script>
   <script type="text/javascript">
      
      function init() {
         var b2Vec2 = Box2D.Common.Math.b2Vec2;
         var b2AABB = Box2D.Collision.b2AABB;
         var b2BodyDef = Box2D.Dynamics.b2BodyDef;
         var b2Body = Box2D.Dynamics.b2Body;
         var b2FixtureDef = Box2D.Dynamics.b2FixtureDef;
         var b2Fixture = Box2D.Dynamics.b2Fixture;
         var b2World = Box2D.Dynamics.b2World;
         var b2PolygonShape = Box2D.Collision.Shapes.b2PolygonShape;
         var b2DebugDraw = Box2D.Dynamics.b2DebugDraw;
      
         var worldScale = 3;
         
         var world = new b2World(new b2Vec2(0, 9.8),true);
         var context = document.getElementById("canvas").getContext("2d");        
         var canvasPosition = getElementPosition(document.getElementById("canvas"));     
         debugDraw();             
         window.setInterval(update,1000/60);

         var tryToAnswer = false;

         createBox(640,30,320,480,b2Body.b2_staticBody);
         createBox(640,30,320,0,b2Body.b2_staticBody);
         createBox(30,480,0,240,b2Body.b2_staticBody);
         createBox(30,480,640,240,b2Body.b2_staticBody);
         
         document.getElementById("canvas").addEventListener("mousedown",function(e){
            var answer = document.getElementById("answer").value;
            if(answer != ""){
               /*We want to destroy de square from the previous try, if any*/
               if (tryToAnswer) {
                  world.DestroyBody(world.GetBodyList());
               };
               createBoxAndMove(40,40,70,440,answer);
               tryToAnswer = true;
            }else{
               alert("Must write an answer!");
            }
         });

         function createBox(width,height,pX,pY,type){
            var bodyDef = new b2BodyDef;
            bodyDef.type = type;
            bodyDef.position.Set(pX/worldScale,pY/worldScale);
            var polygonShape = new b2PolygonShape;
            polygonShape.SetAsBox(width/2/worldScale,height/2/worldScale);
            var fixtureDef = new b2FixtureDef;
            fixtureDef.density = 1.0;
            fixtureDef.friction = 0.5;
            fixtureDef.restitution = 0;
            fixtureDef.shape = polygonShape;
            var body = world.CreateBody(bodyDef);
            body.CreateFixture(fixtureDef);
            return body;
         }

         function createBoxAndMove(width,height,pX,pY,answer){
            var body=createBox(width,height,pX,pY,b2Body.b2_dynamicBody);            
            var force = body.GetMass() * -(answer); 
            body.ApplyImpulse(new b2Vec2(0,force), body.GetWorldCenter() );
         }
         
         function debugDraw(){
            var debugDraw = new b2DebugDraw();
            debugDraw.SetSprite(context);
            debugDraw.SetDrawScale(worldScale);
            debugDraw.SetFillAlpha(0.5);
            debugDraw.SetLineThickness(1.0);
            debugDraw.SetFlags(b2DebugDraw.e_shapeBit | b2DebugDraw.e_jointBit);
            world.SetDebugDraw(debugDraw);
         }
         
         function update() { 
            world.Step(1/60,10,10);
            world.DrawDebugData();
            context.fillStyle = "cyan";
            context.font = "bold 16px sans-serif";
            context.fillText("90m", 18,160);
            context.save(); 
            world.ClearForces();
         };
         
         //http://js-tut.aardon.de/js-tut/tutorial/position.html
         function getElementPosition(element) {
            var elem=element, tagname="", x=0, y=0;
            while((typeof(elem) == "object") && (typeof(elem.tagName) != "undefined")) {
               y += elem.offsetTop;
               x += elem.offsetLeft;
               tagname = elem.tagName.toUpperCase();
               if(tagname == "BODY"){
                  elem=0;
               }
               if(typeof(elem) == "object"){
                  if(typeof(elem.offsetParent) == "object"){
                     elem = elem.offsetParent;
                  }
               }
            }
            return {x: x, y: y};
         }

      };
   
   </script>
   
<footer style="text-align: right;color: lightgrey;">
<a href="https://github.com/ale7714/physics_simulation_demo" target="_blank">Github</a></footer>  
</html>