const gridSize= 20;
let grid= [];

for (let row= 0; row< gridSize; row++){
    grid[row]= [];
    for (let col= 0; col< gridSize; col++){
        grid[row][col]= 0;
    }
}

function createGrid(){
    const gridContainer = document.getElementById("grid-container");
    gridContainer.innerHTML = "";

    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            const cell = document.createElement("div");
            cell.classList.add("cell");
            cell.dataset.row = row;
            cell.dataset.col = col;
            cell.addEventListener("click", () => toggleCell(row, col));
            gridContainer.appendChild(cell);
        }
    }

    updateGrid();
}

function updateGrid() {
    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            const cell = document.querySelector(`.cell[data-row="${row}"][data-col="${col}"]`);
            if (grid[row][col] === 1) {
                cell.classList.add("alive");
            } else {
                cell.classList.remove("alive");
            }
        }
    }
}

function toggleCell(row, col){
    const cell= document.querySelector(`.cell[data-row="${row}"][data-col="${col}"]`);
    if (grid[row][col]=== 0){
        grid[row][col]= 1;
        cell.classList.add("alive");
    } else {
        grid[row][col]= 0;
        cell.classList.remove("alive");
    }
}

function checkNeighbors(row, col){
    const neighbors= [
        [-1, -1], [-1, 0], [-1, 1],
        [0, -1],          [0, 1],
        [1, -1], [1, 0], [1, 1]
    ];
    let aliveCount= 0;

    for (const [x, y] of neighbors) {
        const newRow= row + x;
        const newCol= col + y;
        if (newRow >= 0 && newRow < gridSize && newCol >= 0 && newCol < gridSize) {
            aliveCount+= grid[newRow][newCol];
        }
    }
    return aliveCount;
}

function nextGeneration() {
    const newGrid = [];

    for (let row = 0; row < gridSize; row++) {
        newGrid[row] = [];
        for (let col = 0; col < gridSize; col++) {
            const neighbors = checkNeighbors(row, col);
            const cell = grid[row][col];

            if (cell === 1) {
                // Live cell rules
                newGrid[row][col] = (neighbors === 2 || neighbors === 3) ? 1 : 0;
            } else {
                // Dead cell rules
                newGrid[row][col] = (neighbors === 3) ? 1 : 0;
            }
        }
    }

    grid = newGrid;
    updateGrid();
}


let intervalId = null;

function startSimulation() {
    if (!intervalId) {
        intervalId = setInterval(nextGeneration, 250);
    }
}

function stopSimulation() {
    clearInterval(intervalId);
    intervalId = null;
}

function jumpGenerations() {
    let count = 0;
    const steps = 23;

    const interval = setInterval(() => {
        nextGeneration();
        count++;

        if (count >= steps) {
            clearInterval(interval);
        }
    }, 150);
}



document.addEventListener("DOMContentLoaded", () => {
    createGrid();

    console.log("DOM fully loaded");
    console.log("Next button:", document.getElementById("next-btn"));


    document.getElementById("next-btn").addEventListener("click", nextGeneration);
    document.getElementById("jump-btn").addEventListener("click", jumpGenerations);
    document.getElementById("start-btn").addEventListener("click", startSimulation);
    document.getElementById("stop-btn").addEventListener("click", stopSimulation);
});


