import Bullet from './bullet.js';
import Asteroid from './asteroid.js';

// const laserEffect = new Audio('laser2.mp3');
const canvas = document.querySelector('.game');
const userscore = document.querySelector('.userscore');
const style = document.querySelector('style');
const sendscore = document.querySelector('#sendscore');
const location = sendscore.getAttribute('href');

function displayForm(){
    form.style.display = 'block';
    insideForm.forEach(element => {
        element.style.display = 'block';
    });
}


canvas.style.cursor = 'none';


canvas.height = window.innerHeight;
canvas.width = window.innerWidth-0.2125*window.innerWidth;

const ctx = canvas.getContext('2d');

var x = canvas.height*(3/4);
var y = canvas.height*(3/4);
var score = 0;

function distance(x1,y1,x2,y2){
    return Math.sqrt(Math.pow(x2-x1,2)+Math.pow(y2-y1,2));
}

function collionDetection(obj1,obj2){
    if(distance(obj1.x,obj1.y,obj2.x,obj2.y) < obj1.radius+obj2.radius){
        return true;
    }
    return false;
}

function getRandomColor(){
    return `rgb(${Math.random()*255},${Math.random()*255},${Math.random()*255})`;
}

function updateXY(e){
    x = e.clientX;
    if(e.clientY < canvas.height*(1/2) || e.clientY >= canvas.height-45){
        if(e.clientY < canvas.height*(1/2)){
            y = canvas.height*(1/2);
        }
        else{
            y = canvas.height-45;
        }
    }else{
        y = e.clientY;
    }
}

canvas.addEventListener('mousemove',updateXY);

const spaceship = new Image();
spaceship.src = 'Assets/spaceship.png';

const background = new Image();
background.src = 'Assets/background.png';

var bullets = [];
var asteroids = [];
var gameover = false;
var asteroiddrawframe = 0;
var bulletdrawframe = 0;

function animate() {
    if(gameover){
        const displayscore = document.querySelector('.displayscore');
        displayscore.innerText = `Your Score: ${score}`;
        canvas.style.cursor = 'default';
        style.innerText = '';
        context=null;
        return;
    }
    requestAnimationFrame(animate);
    // background for canvas
    // ctx.fillStyle = 'rgb(17, 20, 27)';
    // ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(background,0,0);

    ctx.drawImage(spaceship,x-25,y-50,50,90);

    //draw asteroid every 75 frames
    if(asteroiddrawframe % 150 == 0){
        let radius = Math.random()*30+50;
        asteroids.push(new Asteroid(Math.random()*(canvas.width-radius*2)+radius,0,radius));
    }
    asteroiddrawframe++;
    asteroiddrawframe = asteroiddrawframe == 150? 0 : asteroiddrawframe;

    //draw asteroid every 3 frames
    if(bulletdrawframe%3==0){
        bullets.push(new Bullet(x,y-20));
    }
    bulletdrawframe++;
    bulletdrawframe = bulletdrawframe == 3? 0 : bulletdrawframe;

    //draw bullets
    bullets.forEach(bullet => {
        bullet.draw(ctx);
        asteroids.forEach(asteroid => {
            if(collionDetection(bullet,asteroid)){
                bullets = bullets.filter(bullet => !collionDetection(bullet,asteroid));
                asteroid.radius--;
            }
            if(asteroid.radius < 15){
                score++;
                sendscore.setAttribute('href',`${location}score=${score}`);
                userscore.setAttribute('value',score);
                userscore.innerHTML = `<p style='color:rgb(156, 208, 238);margin-top:4% !important'>Your Score</p><p>${score}</p>`;
            }
            asteroids = asteroids.filter(asteroid => asteroid.radius >= 15);
        })
    });

    //draw asteroids
    asteroids.forEach(asteroid => {
        asteroid.draw(ctx);
        if(asteroid.y - asteroid.radius > canvas.height){
            gameover = !gameover;
            asteroids = asteroids.filter(asteroid => asteroid.y < canvas.height);
        }
    })
    bullets = bullets.filter(bullet => bullet.y > 0);
}


animate();

