// patterns.js - Predefined patterns for Game of Life
const patterns = {
  // Still life patterns
  block: [
      [0, 0, 0, 0],
      [0, 1, 1, 0],
      [0, 1, 1, 0],
      [0, 0, 0, 0]
  ],
  beehive: [
      [0, 0, 0, 0, 0, 0],
      [0, 0, 1, 1, 0, 0],
      [0, 1, 0, 0, 1, 0],
      [0, 0, 1, 1, 0, 0],
      [0, 0, 0, 0, 0, 0]
  ],
  loaf: [
      [0, 0, 0, 0, 0, 0],
      [0, 0, 1, 1, 0, 0],
      [0, 1, 0, 0, 1, 0],
      [0, 0, 1, 0, 1, 0],
      [0, 0, 0, 1, 0, 0],
      [0, 0, 0, 0, 0, 0]
  ],
  // Oscillator patterns
  blinker: [
      [0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0],
      [0, 1, 1, 1, 0],
      [0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0]
  ],
  beacon: [
      [0, 0, 0, 0, 0, 0],
      [0, 1, 1, 0, 0, 0],
      [0, 1, 1, 0, 0, 0],
      [0, 0, 0, 1, 1, 0],
      [0, 0, 0, 1, 1, 0],
      [0, 0, 0, 0, 0, 0]
  ],
  toad: [
      [0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0],
      [0, 0, 1, 1, 1, 0],
      [0, 1, 1, 1, 0, 0],
      [0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0]
  ],
  // Spaceship patterns
  glider: [
      [0, 0, 0, 0, 0],
      [0, 0, 1, 0, 0],
      [0, 0, 0, 1, 0],
      [0, 1, 1, 1, 0],
      [0, 0, 0, 0, 0]
  ]
};

function loadPattern(patternName) {
  if (!patterns[patternName]) {
      console.error('Pattern not found:', patternName);
      return;
  }

  // Reset the grid first
  for (let row = 0; row < gridSize; row++) {
      for (let col = 0; col < gridSize; col++) {
          grid[row][col] = 0;
      }
  }

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
  saveInitialState();
}

// Event listener for pattern loading
document.addEventListener("DOMContentLoaded", () => {
  const patternSelect = document.getElementById("pattern-select");
  const loadPatternBtn = document.getElementById("load-pattern-btn");
  
  loadPatternBtn.addEventListener("click", () => {
      const selectedPattern = patternSelect.value;
      if (selectedPattern) {
          loadPattern(selectedPattern);
      }
  });
  initialGrid = cloneGrid(grid);
});