import React from 'react';
import PropTypes from 'prop-types';
import './Workflows.css';
import Workflow from './Workflow';

const Workflows = (props) => {
  const listItems = props.workflows.map((workflow) => (
    <li key={workflow.id.toString()}>
      <Workflow workflow={workflow} />
    </li>
  ));

  return <ul>{listItems}</ul>;
};

Workflows.propTypes = {
  workflows: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.string,
      pipelineNumber: PropTypes.number,
      triggeredBy: PropTypes.shape({ login: PropTypes.string, avatar_url: PropTypes.string }),
    })
  ),
};

export default Workflows;
