import React from 'react';
import ReactDOM from 'react-dom';
import styled from 'styled-components';
import App from './App';

const Body = styled.div`
  display: flex;
  justify-content: center;
  margin: 5em;
  text-align: center;
`;

ReactDOM.render(
  <Body>
    <React.StrictMode>
      <App />
    </React.StrictMode>
  </Body>,
  document.getElementById('root')
);
