import React from 'react';
import Main from './component/Main';

const App = () => {
  if (localStorage.getItem('circle-token')) {
    return (
      <div>
        < />
      </div>
    );
  } else {
    return (
      <div>
        <Main />
      </div>
    );
  }
};

export default App;
