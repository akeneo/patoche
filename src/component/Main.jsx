import React, { useEffect, useState } from 'react';
import Workflows from './Worflows';
import PropTypes from 'prop-types';
import styled from 'styled-components';

const Main = (props) => {
  const [workflowIdsWithActiveDeployment, setWorkflowIdsWithActiveDeployment] = useState({ ids: [], isLoading: true });
  const [errorMessage, setErrorMessage] = useState('');

  useEffect(() => {
    const workflowData = [];
    const circleCiToken = props.circleToken;
    const circleCiApiBaseUrl = 'https://circleci.com/api/v2';

    const getWorkflowIdsWithActiveDeploymentByPage = async (nextPageToken) => {
      let pipelineApiUrl = `${circleCiApiBaseUrl}/project/gh/akeneo/onboarder/pipeline?circle-token=${circleCiToken}`;
      if ('' !== nextPageToken) {
        pipelineApiUrl = `${pipelineApiUrl}&page-token=${nextPageToken}`;
      }
      const pipelinesResponse = await fetch(pipelineApiUrl);

      return await pipelinesResponse
        .json()
        .then(async (pipelines) => {
          await Promise.all(
            pipelines.items.map(async (pipeline) => {
              const pipelineWorkflowsResponse = await fetch(
                `${circleCiApiBaseUrl}/pipeline/${pipeline.id}/workflow?circle-token=${circleCiToken}`
              );

              pipelineWorkflowsResponse
                .json()
                .then(async (workflows) => {
                  await Promise.all(
                    workflows.items.map(async (workflow) => {
                      const workflowJobsResponse = await fetch(
                        `${circleCiApiBaseUrl}/workflow/${workflow.id}/job?circle-token=${circleCiToken}`
                      );

                      workflowJobsResponse
                        .json()
                        .then(async (jobs) => {
                          await Promise.all(
                            jobs.items.map(async (job) => {
                              if ('cleanup-all-environment?' === job.name && 'on_hold' === job.status) {
                                workflowData.push({
                                  id: workflow.id,
                                  pipelineNumber: workflow.pipeline_number,
                                  triggeredBy: pipeline.trigger.actor,
                                });
                              } else {
                                  console.info('Job name and status not relevant');
                              }
                            })
                          );
                        })
                        .catch((error) => setErrorMessage(error.message));
                    })
                  );
                })
                .catch((error) => setErrorMessage(error.message));
            })
          );

          return pipelines.next_page_token;
        })
        .catch((error) => setErrorMessage(error.message));
    };

    const getWorkflowIdsWithActiveDeployment = async () => {
      let nextPageToken = '';

      for (let page = 0; page < 5; page++) {
        nextPageToken = await getWorkflowIdsWithActiveDeploymentByPage(nextPageToken);
      }

      setWorkflowIdsWithActiveDeployment({ workflows: workflowData, isLoading: false });
    };

    getWorkflowIdsWithActiveDeployment();
  }, [props]);

  const LoadingSpinner = styled.div`
    color: #4ca8e0;
    font-size: 90px;
    text-indent: -9999em;
    overflow: hidden;
    width: 1em;
    height: 1em;
    border-radius: 50%;
    margin: 10px;
    transform: translateZ(0);
    animation: load6 1.7s infinite ease, round 1.7s infinite ease;

    @keyframes load6 {
      0% {
        box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em,
          0 -0.83em 0 -0.477em;
      }

      5%,
      95% {
        box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em,
          0 -0.83em 0 -0.477em;
      }

      10%,
      59% {
        box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em,
          -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
      }

      20% {
        box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em,
          -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
      }

      38% {
        box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em,
          -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
      }

      100% {
        box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em,
          0 -0.83em 0 -0.477em;
      }
    }

    @keyframes load6 {
      0% {
        box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em,
          0 -0.83em 0 -0.477em;
      }

      5%,
      95% {
        box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em,
          0 -0.83em 0 -0.477em;
      }

      10%,
      59% {
        box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em,
          -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
      }

      20% {
        box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em,
          -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
      }

      38% {
        box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em,
          -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
      }

      100% {
        box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em,
          0 -0.83em 0 -0.477em;
      }
    }

    @keyframes round {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes round {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  `;

  return (
    <div>
      {errorMessage ? (
        <p>Encountered error: &quot{errorMessage}&quot</p>
      ) : (
        <div>
          {workflowIdsWithActiveDeployment.isLoading ? (
            <LoadingSpinner></LoadingSpinner>
          ) : (
            <Workflows workflows={workflowIdsWithActiveDeployment.workflows} />
          )}
        </div>
      )}
    </div>
  );
};

Main.propTypes = {
  circleToken: PropTypes.string,
};

export default Main;
