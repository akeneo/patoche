import React from 'react';
import PropTypes from 'prop-types';
import Workflow from './Workflow';
import styled from 'styled-components';

const Workflows = (props) => {
  const Li = styled.li`
    list-style: none;
  `;

  const Ul = styled.ul`
    list-style-type: none;
    padding: 0;
  `;

  const listItems = props.workflows.map((workflow) => (
    <Li key={workflow.id.toString()}>
      <Workflow workflow={workflow} />
    </Li>
  ));

  return <Ul>{listItems}</Ul>;
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
