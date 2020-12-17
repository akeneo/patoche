import React from 'react';
import PropTypes from 'prop-types';
import './Workflow.css';

const Workflow = (props) => {
  const url = `https://app.circleci.com/pipelines/github/akeneo/onboarder/${props.workflow.pipelineNumber}/workflows/${props.workflow.id}`;

  return (
    <span>
      <img src={props.workflow.triggeredBy.avatar_url} alt={props.workflow.triggeredBy.login} />
      <a href={url}>{props.workflow.id}</a>
    </span>
  );
};

Workflow.propTypes = {
  workflow: PropTypes.shape({
    id: PropTypes.string,
    pipelineNumber: PropTypes.number,
    triggeredBy: PropTypes.shape({ login: PropTypes.string, avatar_url: PropTypes.string }),
  }),
};

export default Workflow;
