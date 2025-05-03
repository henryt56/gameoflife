<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>About Conway's Game of Life</title>
    <style>
        .about-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            background-color: #f5f5f5;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .rule-box {
            background-color: #e0f0ff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #04AA6D;
            transition: transform 0.2s;
        }
        
        .rule-box:hover {
            transform: translateX(5px);
        }
        
        .patterns-container {
            margin-top: 30px;
        }
        
        .pattern-category {
            margin-bottom: 30px;
        }
        
        .pattern-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
            justify-content: center;
        }
        
        .pattern-box {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            width: 180px;
            text-align: center;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .pattern-box:hover {
            transform: translateY(-5px);
        }
        
        .pattern-title {
            color: #04AA6D;
            margin-top: 0;
            border-bottom: 2px solid #04AA6D;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .pattern-grid-display {
            display: grid;
            grid-template-columns: repeat(6, 15px);
            grid-template-rows: repeat(6, 15px);
            gap: 1px;
            margin: 0 auto;
            justify-content: center;
        }
        
        .pattern-cell {
            width: 15px;
            height: 15px;
            background-color: white;
            border: 1px solid #ddd;
        }
        
        .pattern-cell.alive {
            background-color: black;
        }
        
        .pattern-info {
            margin-top: 10px;
            font-size: 0.9em;
        }
        
        .section-title {
            color: #04AA6D;
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #04AA6D;
        }
        
        @media (max-width: 600px) {
            .pattern-grid {
                gap: 10px;
            }
            
            .pattern-box {
                width: 130px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="about-container">
        <h1 class="section-title">About Conway's Game of Life</h1>
        
        <h2 class="section-title">What is Game of Life?</h2>
        <p>Conway's Game of Life is a cellular automaton devised by the British mathematician John Horton Conway in 1970. It is a zero-player game, meaning that its evolution is determined by its initial state, requiring no further input.</p>
        
        <h2 class="section-title">Rules</h2>
        <p>The universe of the Game of Life is an infinite, two-dimensional orthogonal grid of square cells, each of which is in one of two possible states: alive or dead. Every cell interacts with its eight neighbors, which are the cells that are horizontally, vertically, or diagonally adjacent.</p>
        
        <div class="rule-box">
            <p><strong>Rule 1:</strong> Any live cell with fewer than two live neighbors dies, as if by underpopulation.</p>
        </div>
        <div class="rule-box">
            <p><strong>Rule 2:</strong> Any live cell with two or three live neighbors lives on to the next generation.</p>
        </div>
        <div class="rule-box">
            <p><strong>Rule 3:</strong> Any live cell with more than three live neighbors dies, as if by overpopulation.</p>
        </div>
        <div class="rule-box">
            <p><strong>Rule 4:</strong> Any dead cell with exactly three live neighbors becomes a live cell, as if by reproduction.</p>
        </div>
        
        <div class="patterns-container">
            <h2 class="section-title">Common Patterns</h2>
            <p>Over the years, enthusiasts have discovered numerous interesting patterns in the Game of Life:</p>
            
            <div class="pattern-category">
                <h3>Still Life Patterns</h3>
                <p>These patterns remain unchanged from one generation to the next.</p>
                <div class="pattern-grid">
                    <!-- Block Pattern -->
                    <div class="pattern-box">
                        <h4 class="pattern-title">Block</h4>
                        <div class="pattern-grid-display" id="block-pattern">
                            <!-- Pattern will be filled by JavaScript -->
                        </div>
                        <div class="pattern-info">
                            A simple 2×2 square that remains stable.
                        </div>
                    </div>
                    
                    <!-- Beehive Pattern -->
                    <div class="pattern-box">
                        <h4 class="pattern-title">Beehive</h4>
                        <div class="pattern-grid-display" id="beehive-pattern">
                            <!-- Pattern will be filled by JavaScript -->
                        </div>
                        <div class="pattern-info">
                            A 6-cell pattern that resembles a beehive.
                        </div>
                    </div>
                    
                    <!-- Loaf Pattern -->
                    <div class="pattern-box">
                        <h4 class="pattern-title">Loaf</h4>
                        <div class="pattern-grid-display" id="loaf-pattern">
                            <!-- Pattern will be filled by JavaScript -->
                        </div>
                        <div class="pattern-info">
                            A 7-cell stable pattern.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pattern-category">
                <h3>Oscillators</h3>
                <p>These patterns return to their initial state after a finite number of generations.</p>
                <div class="pattern-grid">
                    <!-- Blinker Pattern -->
                    <div class="pattern-box">
                        <h4 class="pattern-title">Blinker</h4>
                        <div class="pattern-grid-display" id="blinker-pattern">
                            <!-- Pattern will be filled by JavaScript -->
                        </div>
                        <div class="pattern-info">
                            Period: 2<br>
                            Alternates between horizontal and vertical line.
                        </div>
                    </div>
                    
                    <!-- Beacon Pattern -->
                    <div class="pattern-box">
                        <h4 class="pattern-title">Beacon</h4>
                        <div class="pattern-grid-display" id="beacon-pattern">
                            <!-- Pattern will be filled by JavaScript -->
                        </div>
                        <div class="pattern-info">
                            Period: 2<br>
                            Two oscillating blocks.
                        </div>
                    </div>
                    
                    <!-- Toad Pattern -->
                    <div class="pattern-box">
                        <h4 class="pattern-title">Toad</h4>
                        <div class="pattern-grid-display" id="toad-pattern">
                            <!-- Pattern will be filled by JavaScript -->
                        </div>
                        <div class="pattern-info">
                            Period: 2<br>
                            A 6-cell oscillator.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pattern-category">
                <h3>Spaceships</h3>
                <p>These patterns move across the grid.</p>
                <div class="pattern-grid">
                    <!-- Glider Pattern -->
                    <div class="pattern-box">
                        <h4 class="pattern-title">Glider</h4>
                        <div class="pattern-grid-display" id="glider-pattern">
                            <!-- Pattern will be filled by JavaScript -->
                        </div>
                        <div class="pattern-info">
                            Moves diagonally across the grid, returning to its original shape every 4 generations.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <h2 class="section-title">Try It Yourself!</h2>
        <p>Go back to our simulation and try creating these patterns or design your own!</p>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="index.html"><button>Back to Home</button></a>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="game.php"><button>Back to Game</button></a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Define patterns
        const patterns = {
            block: [
                [0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0],
                [0, 0, 1, 1, 0, 0],
                [0, 0, 1, 1, 0, 0],
                [0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0]
            ],
            beehive: [
                [0, 0, 0, 0, 0, 0],
                [0, 0, 1, 1, 0, 0],
                [0, 1, 0, 0, 1, 0],
                [0, 0, 1, 1, 0, 0],
                [0, 0, 0, 0, 0, 0],
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
            blinker: [
                [0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0],
                [0, 0, 1, 1, 1, 0],
                [0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0]
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
            glider: [
                [0, 0, 0, 0, 0, 0],
                [0, 0, 1, 0, 0, 0],
                [0, 0, 0, 1, 0, 0],
                [0, 1, 1, 1, 0, 0],
                [0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0]
            ]
        };
        
        // Function to render a pattern
        function renderPattern(patternId, patternData) {
            const container = document.getElementById(patternId);
            if (!container) return;
            
            container.innerHTML = '';
            
            for (let y = 0; y < patternData.length; y++) {
                for (let x = 0; x < patternData[y].length; x++) {
                    const cell = document.createElement('div');
                    cell.className = 'pattern-cell' + (patternData[y][x] ? ' alive' : '');
                    container.appendChild(cell);
                }
            }
        }
        
        // Render all patterns when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            renderPattern('block-pattern', patterns.block);
            renderPattern('beehive-pattern', patterns.beehive);
            renderPattern('loaf-pattern', patterns.loaf);
            renderPattern('blinker-pattern', patterns.blinker);
            renderPattern('beacon-pattern', patterns.beacon);
            renderPattern('toad-pattern', patterns.toad);
            renderPattern('glider-pattern', patterns.glider);
        });
    </script>
</body>
</html>