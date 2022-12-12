function Bullet(x,y){
    this.x = x;
    this.y = y;
    this.radius = 5;
    this.draw = function(ctx){
        ctx.beginPath();
        ctx.arc(this.x,this.y-=7,this.radius,0,Math.PI * 2,false);
        ctx.strokeStyle = 'white';
        ctx.stroke();
    }
}

export default Bullet;