// Global variables
let gridSize = 20;
let grid = [];
let generation = 0;
let population = 0;
let intervalId = null;
let gameSessionId = null;
let initialGrid = [];

// Initialize grid
function initializeGrid() {
    grid = [];
    for (let row = 0; row < gridSize; row++) {
        grid[row] = [];
        for (let col = 0; col < gridSize; col++) {
            grid[row][col] = 0;
        }
    }
}

// Clone a grid
function cloneGrid(grid) {
    return grid.map(row => [...row]);
}

// Create grid on the page
function createGrid() {
    const gridContainer = document.getElementById("grid-container");
    gridContainer.innerHTML = "";
    
    // Set grid CSS
    gridContainer.style.gridTemplateColumns = `repeat(${gridSize}, 20px)`;
    gridContainer.style.gridTemplateRows = `repeat(${gridSize}, 20px)`;
    
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

// Update grid display
function updateGrid() {
    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            const cell = document.querySelector(`.cell[data-row="${row}"][data-col="${col}"]`);
            if (cell) {
                if (grid[row][col] === 1) {
                    cell.classList.add("alive");
                } else {
                    cell.classList.remove("alive");
                }
            }
        }
    }
}

// Toggle cell state
function toggleCell(row, col) {
    grid[row][col] = grid[row][col] === 0 ? 1 : 0;
    updateGrid();
    updateStats();
    
    // Save initial state after user interaction
    setInitialGrid();
}

// Set initial grid state
function setInitialGrid() {
    initialGrid = cloneGrid(grid);
    
    // Update React component if it exists
    if (window.updateReactComponent) {
        window.updateReactComponent(initialGrid, grid, gridSize);
    }
}

// Count neighbors for a cell
function checkNeighbors(row, col) {
    const neighbors = [
        [-1, -1], [-1, 0], [-1, 1],
        [0, -1],          [0, 1],
        [1, -1], [1, 0], [1, 1]
    ];
    let aliveCount = 0;
    
    for (const [x, y] of neighbors) {
        const newRow = row + x;
        const newCol = col + y;
        if (newRow >= 0 && newRow < gridSize && newCol >= 0 && newCol < gridSize) {
            aliveCount += grid[newRow][newCol];
        }
    }
    return aliveCount;
}

// Calculate next generation
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
    generation++;
    updateGrid();
    updateStats();
    
    // Update React component
    if (window.updateReactComponent) {
        window.updateReactComponent(initialGrid, grid, gridSize);
    }
    
    // Update game session in database if we're logged in
    if (gameSessionId) {
        updateGameSession();
    }
}

// Update game stats
function updateStats() {
    // Count population
    population = 0;
    for (let row = 0; row < gridSize; row++) {
        for (let col = 0; col < gridSize; col++) {
            population += grid[row][col];
        }
    }
    
    // Update display
    const generationElement = document.getElementById("generation-count");
    const populationElement = document.getElementById("population-count");
    
    if (generationElement) {
        generationElement.textContent = generation;
    }
    
    if (populationElement) {
        populationElement.textContent = population;
    }
}

// Start simulation
function startSimulation() {
    if (!intervalId) {
        intervalId = setInterval(nextGeneration, 250);
    }
}

// Stop simulation
function stopSimulation() {
    clearInterval(intervalId);
    intervalId = null;
    
    // End the game session when stopping
    if (gameSessionId) {
        endGameSession();
    }
}

// Jump 23 generations
function jumpGenerations() {
    let count = 0;
    const steps = 23;
    
    const jumpInterval = setInterval(() => {
        nextGeneration();
        count++;
        
        if (count >= steps) {
            clearInterval(jumpInterval);
        }
    }, 50);
}

// Reset simulation
function resetSimulation() {
    stopSimulation();
    initializeGrid();
    generation = 0;
    population = 0;
    updateGrid();
    updateStats();
    
    // Reset initial grid
    setInitialGrid();
}

// Change grid size
function changeGridSize() {
    const sizeSelect = document.getElementById("grid-size");
    if (sizeSelect) {
        gridSize = parseInt(sizeSelect.value);
    }
    
    resetSimulation();
    createGrid();
}

// Load pattern
function loadPattern(patternName) {
    if (!patterns[patternName]) {
        console.error('Pattern not found:', patternName);
        return;
    }

    // Reset the grid first
    resetSimulation();

    const pattern = patterns[patternName];
    const patternHeight = pattern.length;
    const patternWidth = pattern[0].length;
    
    // Center the pattern in the grid
    const startRow = Math.floor((gridSize - patternHeight) / 2);
    const startCol = Math.floor((gridSize - patternWidth) / 2);
    
    // Copy pattern to grid
    for (let row = 0; row < patternHeight; row++) {
        for (let col = 0; col < patternWidth; col++) {
            if (startRow + row < gridSize && startCol + col < gridSize) {
                grid[startRow + row][startCol + col] = pattern[row][col];
            }
        }
    }
    
    // Update the grid display
    updateGrid();
    updateStats();
    
    // Save as initial state
    setInitialGrid();
}

// Update game session data
function updateGameSession() {
    const sessionIdElement = document.getElementById("game-session-id");
    if (!sessionIdElement) return;
    
    const sessionId = sessionIdElement.value;
    
    // Use fetch API to update the session data
    fetch('update_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `session_id=${sessionId}&generations=${generation}&population=${population}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Session updated:', data);
    })
    .catch(error => {
        console.error('Error updating session:', error);
    });
}

// End game session
function endGameSession() {
    const sessionIdElement = document.getElementById("game-session-id");
    if (!sessionIdElement) return;
    
    const sessionId = sessionIdElement.value;
    
    // Update server with final game stats
    fetch('end_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `session_id=${sessionId}&generations=${generation}&population=${population}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Session ended:', data);
    })
    .catch(error => {
        console.error('Error ending session:', error);
    });
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM fully loaded");
    
    // Get game session ID if we're logged in
    const sessionIdElement = document.getElementById("game-session-id");
    if (sessionIdElement) {
        gameSessionId = sessionIdElement.value;
    }
    
    // Initialize grid
    initializeGrid();
    createGrid();
    
    // Set initial grid for React component
    setInitialGrid();
    
    // Set up button event listeners
    const nextBtn = document.getElementById("next-btn");
    if (nextBtn) nextBtn.addEventListener("click", nextGeneration);
    
    const jumpBtn = document.getElementById("jump-btn");
    if (jumpBtn) jumpBtn.addEventListener("click", jumpGenerations);
    
    const startBtn = document.getElementById("start-btn");
    if (startBtn) startBtn.addEventListener("click", startSimulation);
    
    const stopBtn = document.getElementById("stop-btn");
    if (stopBtn) stopBtn.addEventListener("click", stopSimulation);
    
    const resetBtn = document.getElementById("reset-btn");
    if (resetBtn) resetBtn.addEventListener("click", resetSimulation);
    
    const gridSizeSelect = document.getElementById("grid-size");
    if (gridSizeSelect) gridSizeSelect.addEventListener("change", changeGridSize);
    
    const loadPatternBtn = document.getElementById("load-pattern-btn");
    if (loadPatternBtn) {
        loadPatternBtn.addEventListener("click", () => {
            const patternSelect = document.getElementById("pattern-select");
            if (patternSelect && patternSelect.value) {
                loadPattern(patternSelect.value);
            }
        });
    }
    
    // Add event listener for page unload to save session
    window.addEventListener('beforeunload', function() {
        if (gameSessionId) {
            endGameSession();
        }
    });
});