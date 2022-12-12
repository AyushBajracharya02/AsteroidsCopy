function Asteroid(x,y,radius,color){
    this.image = new Image();
    this.image.src = 'Assets/asteroid.png';
    this.x = x;
    this.y = y;
    this.radius = radius;
    this.color = color;
    this.draw = function(ctx){
        // ctx.beginPath();
        // ctx.arc(this.x,this.y++,this.radius,0,Math.PI * 2,false);
        // ctx.fillStyle = this.color;
        // ctx.fill();
        ctx.drawImage(this.image,this.x,this.y+=0.5,this.radius,this.radius*0.75);
    }
}

export default Asteroid;