import React from 'react';
import PropTypes from 'prop-types';
import './Workflows.css';
import Workflow from './Workflow';

const Workflows = (props) => {
  const workflows = props.workflows;
  const listItems = workflows.map((workflow) => (
    <li key={workflow.id.toString()}>
      <Workflow workflow={workflow} />
    </li>
  ));

  return <ul>{listItems}</ul>;
};

Workflows.propTypes = {
  workflows: PropTypes.arrayOf(PropTypes.shape({ id: PropTypes.string, pipelineNumber: PropTypes.number })),
};

export default Workflows;
