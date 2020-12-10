import React from 'react';

function Workflows(props) {
  const workflowIds = props.workflowIds;
  const listItems = workflowIds.map((workflowId) => <li key={workflowId.toString()}>{workflowId}</li>);

  return <ul>{listItems}</ul>;
}

export default Workflows;
