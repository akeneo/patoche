import React from 'react';
import PropTypes from 'prop-types';
import './Workflows.css';

const Workflows = (props) => {
  const workflowIds = props.workflowIds;
  const listItems = workflowIds.map((workflowId) => <li key={workflowId.toString()}>{workflowId}</li>);

  return <ul>{listItems}</ul>;
};

Workflows.propTypes = {
  workflowIds: PropTypes.array,
};

export default Workflows;
