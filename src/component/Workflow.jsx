import React from 'react';
import PropTypes from 'prop-types';
import styled from 'styled-components';

const Workflow = (props) => {
  const url = `https://app.circleci.com/pipelines/github/akeneo/onboarder/${props.workflow.pipelineNumber}/workflows/${props.workflow.id}`;

  const Span = styled.span`
    display: flex;
    align-items: center;
    margin: 5px 0;
  `;

  const Image = styled.img`
    width: 35px;
    height: auto;
    margin-right: 10px;
  `;

  return (
    <Span>
      <Image src={props.workflow.triggeredBy.avatar_url} alt={props.workflow.triggeredBy.login} />
      <a href={url}>{props.workflow.id}</a>
    </Span>
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
