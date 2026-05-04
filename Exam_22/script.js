const canvas = document.getElementById("drawingCanvas");
const ctx = canvas.getContext("2d");
const shapeType = document.getElementById("shapeType");

let startX = null;
let startY = null;
const shapes = [];

function resizeCanvas() {
    canvas.width = canvas.clientWidth;
    canvas.height = canvas.clientWidth * 450 / 800;
    redrawShapes();
}

function getMousePosition(event) {
    const rect = canvas.getBoundingClientRect();
    return {
        x: event.clientX - rect.left,
        y: event.clientY - rect.top
    };
}

function drawLine(x1, y1, x2, y2, color) {
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.strokeStyle = color;
    ctx.lineWidth = 3;
    ctx.stroke();
}

function drawRectangle(x1, y1, x2, y2, color) {
    ctx.beginPath();
    ctx.rect(x1, y1, x2 - x1, y2 - y1);
    ctx.strokeStyle = color;
    ctx.lineWidth = 3;
    ctx.stroke();
}

function drawPoint(x, y) {
    ctx.beginPath();
    ctx.arc(x, y, 4, 0, Math.PI * 2);
    ctx.fillStyle = "blue";
    ctx.fill();
}

function redrawShapes() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    shapes.forEach(function (shape) {
        if (shape.type === "line") {
            drawLine(shape.x1, shape.y1, shape.x2, shape.y2, "red");
        } else if (shape.type === "rectangle") {
            drawRectangle(shape.x1, shape.y1, shape.x2, shape.y2, "green");
        }
    });

    if (startX !== null) {
        drawPoint(startX, startY);
    }
}

canvas.addEventListener("click", function (event) {
    const point = getMousePosition(event);

    if (startX === null) {
        startX = point.x;
        startY = point.y;
        redrawShapes();
    } else {
        shapes.push({
            type: shapeType.value,
            x1: startX,
            y1: startY,
            x2: point.x,
            y2: point.y
        });
        startX = null;
        startY = null;
        redrawShapes();
    }
});

document.getElementById("clearCanvas").addEventListener("click", function () {
    shapes.length = 0;
    startX = null;
    startY = null;
    redrawShapes();
});

window.addEventListener("resize", resizeCanvas);

resizeCanvas();
