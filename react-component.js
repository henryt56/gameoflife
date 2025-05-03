// Game state visualization component
const GameStateVisualizer = ({ initialState, currentState, size = 20 }) => {
  const [gridSize, setGridSize] = React.useState(size);
  
  React.useEffect(() => {
    setGridSize(size);
  }, [size]);
  
  // Render a single grid
  const renderGrid = (grid, title) => {
    return (
      React.createElement("div", { className: "grid-display" },
        React.createElement("h3", null, title),
        React.createElement("div", {
          className: "mini-grid",
          style: {
            display: 'grid',
            gridTemplateColumns: `repeat(${gridSize}, 10px)`,
            gridTemplateRows: `repeat(${gridSize}, 10px)`,
            gap: '1px',
          }
        }, 
        grid.map((row, y) => 
          row.map((cell, x) => 
            React.createElement("div", {
              key: `${y}-${x}`,
              style: {
                width: '10px',
                height: '10px',
                backgroundColor: cell ? 'black' : 'white',
                border: '1px solid #ddd'
              }
            })
          )
        ))
      )
    );
  };
  
  return (
    React.createElement("div", {
      className: "game-state-container",
      style: { display: 'flex', justifyContent: 'space-around' }
    },
    renderGrid(initialState, 'Initial State'),
    renderGrid(currentState, 'Current State'))
  );
};

// Function to update the component from the main JS
window.updateReactComponent = (initialState, currentState, size) => {
  ReactDOM.render(
    React.createElement(GameStateVisualizer, {
      initialState: initialState,
      currentState: currentState,
      size: size
    }),
    document.getElementById('react-component')
  );
};

// Initial render with empty grids
const emptyGrid = Array(20).fill().map(() => Array(20).fill(0));
ReactDOM.render(
  React.createElement(GameStateVisualizer, {
    initialState: emptyGrid,
    currentState: emptyGrid,
    size: 20
  }),
  document.getElementById('react-component')
);