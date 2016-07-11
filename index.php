<!DOCTYPE html>

<html>
<style>
    #gameCanvas {
        display: block;
    }
</style>
<canvas id="gameCanvas"></canvas>

<head>
    <title>
    </title>

    <script>
        var canvas;
        var canvasContext;
        var ballX = 50;
        var ballY = 50;
        var ballSpeedX = 10;
        var ballSpeedY = 4;
        var paddle1Y = 250;
        var paddle2Y = 250;

        const PADDLE_HEIGHT = 100;
        const PADDLE_WIDTH = 5;
        const BALL_SIZE = 5;
        const WIN_CONDITION = 3;

        var showingWinScreen = false;
        var player1score = 0;
        var player2score = 0;
        var player1wins = false;
        var player2wins = false;

        function calculateMousePos(evt) {
            var rect = canvas.getBoundingClientRect();
            var root = document.documentElement;
            var mouseX = evt.clientX - rect.left - root.scrollLeft;
            var mouseY = evt.clientY - rect.top - root.scrollTop;
            return {
                x: mouseX,
                y: mouseY
            };
        }

        function handleMouseClick(evt) {
            if (showingWinScreen) {
                player1score = 0;
                player2score = 0;
                showingWinScreen = false;
            }
        }

        window.onload = function () {
            canvas = document.getElementById('gameCanvas');
            //hide cursor
            canvas.style.cursor = "none";
            canvasContext = canvas.getContext('2d');


            var framesPerSecond = 30;
            setInterval(function () {
                moveEverything();
                drawEverything();
            }, 1000 / framesPerSecond);

            canvas.addEventListener('mousedown', handleMouseClick);

            //
            canvas.addEventListener('mousemove',
                function (evt) {
                    var mousePos = calculateMousePos(evt);
                    paddle1Y = mousePos.y - (PADDLE_HEIGHT / 2);
                }
            );

            canvas.width = window.innerWidth - 20;
            canvas.height = window.innerHeight - 20;

        }


        // reset the ball at center
        function ballReset() {
            if (player1score >= WIN_CONDITION ||
                player2score >= WIN_CONDITION) {
                showingWinScreen = true;

            }

            ballSpeedX = -ballSpeedX;
            ballX = canvas.width / 2;
            ballY = canvas.height / 2;
        }

        function moveEverything() {

            cpuMovement();

            ballX += ballSpeedX;
            ballY += ballSpeedY;

            //collision with right paddle
            if (ballX >= canvas.width) {
                if ((ballY > paddle2Y) &&
                    (ballY < paddle2Y + PADDLE_HEIGHT)) {
                    ballSpeedX = -ballSpeedX;
                    //hit the ball at an angle
                    var deltaY = ballY - (paddle2Y + PADDLE_HEIGHT / 2);
                    ballSpeedY = deltaY * 0.35;

                } else {

                    player1score++;
                    ballReset();
                }
            }
            if (ballY >= canvas.height) {
                ballSpeedY = -ballSpeedY;
            }
            //colision with left paddle
            if (ballX <= 0) {
                if ((ballY > paddle1Y) &&
                    (ballY < paddle1Y + PADDLE_HEIGHT)) {
                    ballSpeedX = -ballSpeedX;
                    //hit the ball at an angle
                    var deltaY = ballY - (paddle1Y + PADDLE_HEIGHT / 2);
                    ballSpeedY = deltaY * 0.35;

                } else {

                    player2score++;
                    ballReset();
                }
            }

            if (ballY <= 0) {
                ballSpeedY = -ballSpeedY;
            }
        }
        //CPU movement logic
        function cpuMovement() {
            var paddle2YCenter = paddle2Y + (PADDLE_HEIGHT / 2);
            if (paddle2YCenter < ballY - 35) {
                paddle2Y += 6;
            } else if (paddle2YCenter > ballY + 35) {
                paddle2Y -= 6;
            }
        }

        function drawNet() {
            for (var i = 0; i < canvas.height; i += 40) {
                colorRect(canvas.width / 2 - 1, i, 2, 20, 'white');
            };
        }

        function drawEverything() {
            // next line blanks out the screen with black
            colorRect(0, 0, canvas.width, canvas.height, 'black');
            drawNet();

            if (showingWinScreen) {
                canvasContext.fillStyle = 'white';
                canvasContext.font = 'bold 30px sans-serif';

                if (player1score >= WIN_CONDITION) {
                    canvasContext.fillText("Player 1 wins", canvas.width / 2 - 100,
                        canvas.height / 2);
                } else
                    canvasContext.fillText("Player 2 wins", canvas.width / 2 - 100,
                        canvas.height / 2);

                canvasContext.font = '12px sans-serif';
                canvasContext.fillText("click to continue", 100, 100);
                return;
            }

            // left player paddle
            colorRect(0, paddle1Y, PADDLE_WIDTH, PADDLE_HEIGHT, 'white');
            // right cpu paddle
            colorRect(canvas.width - PADDLE_WIDTH, paddle2Y, PADDLE_WIDTH, PADDLE_HEIGHT, 'white');
            //next line draws the ball
            colorCircle(ballX, ballY, BALL_SIZE, 'white');

            canvasContext.fillText(player1score, 50, 50);
            canvasContext.fillText(player2score, canvas.width - 50, 50);

        }

        function colorCircle(centerX, centerY, radius, drawColor) {
            canvasContext.fillStyle = drawColor;
            canvasContext.beginPath();
            canvasContext.arc(centerX, centerY, radius, 0, Math.PI * 2, true);
            canvasContext.fill();
        }

        function colorRect(leftX, topY, width, height, drawColor) {
            canvasContext.fillStyle = drawColor;
            canvasContext.fillRect(leftX, topY, width, height);
        }
    </script>

</head>

<body>
</body>

</html>