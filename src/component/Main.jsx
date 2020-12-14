import React, { useEffect, useState } from 'react';
import Workflows from './Worflows';
import PropTypes from 'prop-types';

const Main = (props) => {
  const [workflowIdsWithActiveDeployment, setWorkflowIdsWithActiveDeployment] = useState({ ids: [], isLoading: true });
  const [errorMessage, setErrorMessage] = useState('');

  useEffect(() => {
    const workflowIds = [];
    const circleCiToken = props.circleToken;
    const circleCiApiBaseUrl = 'https://circleci.com/api/v2';

    const getWorkflowIdsWithActiveDeployment = async () => {
      const pipelinesResponse = await fetch(
        `${circleCiApiBaseUrl}/project/gh/akeneo/onboarder/pipeline?circle-token=${circleCiToken}`
      );
      await pipelinesResponse
        .json()
        .then(async (pipelines) => {
          for (const pipeline of pipelines.items) {
            const pipelineWorkflowsResponse = await fetch(
              `${circleCiApiBaseUrl}/pipeline/${pipeline.id}/workflow?circle-token=${circleCiToken}`
            );

            pipelineWorkflowsResponse
              .json()
              .then(async (workflows) => {
                for (const workflow of workflows.items) {
                  const workflowJobsResponse = await fetch(
                    `${circleCiApiBaseUrl}/workflow/${workflow.id}/job?circle-token=${circleCiToken}`
                  );

                  workflowJobsResponse
                    .json()
                    .then((result) => {
                      result.items.forEach((job) => {
                        if ('clean-up-upgraded-environment?' === job.name && 'on_hold' === job.status) {
                          workflowIds.push(workflow.id);
                        }
                      });
                    })
                    .catch((error) => setErrorMessage(error.message));
                }
              })
              .catch((error) => setErrorMessage(error.message));
          }
        })
        .catch((error) => setErrorMessage(error.message));

      setWorkflowIdsWithActiveDeployment({ ids: workflowIds, isLoading: false });
    };

    getWorkflowIdsWithActiveDeployment();
  }, [props]);

  return (
    <div>
      {errorMessage ? (
        <p>Encountered error: &quot{errorMessage}&quot</p>
      ) : (
        <div>
          {workflowIdsWithActiveDeployment.isLoading ? (
            <p>Loading data from CircleCI</p>
          ) : (
            <Workflows workflowIds={workflowIdsWithActiveDeployment.ids} />
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
