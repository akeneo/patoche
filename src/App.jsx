import React from 'react';
import CircleCiTokenForm from './component/CircleCiTokenForm';
import Main from './component/Main';

const App = () => {
  if (null === localStorage.getItem('circle-token') || '' === localStorage.getItem('circle-token')) {
    return (
      <div>
        <CircleCiTokenForm />
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
