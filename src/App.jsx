import React, { useState } from 'react';
import CircleCiTokenForm from './component/CircleCiTokenForm';
import Main from './component/Main';

const App = () => {
  const [circleToken, setCircleToken] = useState(localStorage.getItem('circle-token'));

  if (null === circleToken || '' === circleToken) {
    return (
      <div>
        <CircleCiTokenForm state={{ circleToken: setCircleToken }} />
      </div>
    );
  } else {
    return (
      <div>
        <Main circleToken={circleToken} />
      </div>
    );
  }
};

export default App;
