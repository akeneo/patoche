import React from 'react';
import PropTypes from 'prop-types';

const Workflow = (props) => {
  const url = `https://app.circleci.com/pipelines/github/akeneo/onboarder/${props.workflow.pipelineNumber}/workflows/${props.workflow.id}`;

  return (
    <span>
      <a href={url}>{props.workflow.id}</a>
    </span>
  );
};

Workflow.propTypes = {
  workflow: PropTypes.shape({ id: PropTypes.string, pipelineNumber: PropTypes.number }),
};

export default Workflow;
