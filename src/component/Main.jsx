import React, { useEffect, useState } from 'react';
import Workflows from './Worflows';

const Main = () => {
  const [workflowIdsWithActiveDeployment, setWorkflowIdsWithActiveDeployment] = useState([]);
  const [errorMessage, setErrorMessage] = useState('');
  const workflowIds = [];
  const circleCiToken = localStorage.getItem('circle-token');

  const getWorkflowIdsWithActiveDeployment = async () => {
    const pipelinesResponse = await fetch(
      `https://circleci.com/api/v2/project/gh/akeneo/onboarder/pipeline?circle-token=${circleCiToken}`
    );
    await pipelinesResponse
      .json()
      .then(async (pipelines) => {
        for (const pipeline of pipelines.items) {
          const pipelineWorkflowsResponse = await fetch(
            `https://circleci.com/api/v2/pipeline/${pipeline.id}/workflow?circle-token=${circleCiToken}`
          );

          pipelineWorkflowsResponse
            .json()
            .then(async (workflows) => {
              for (const workflow of workflows.items) {
                const workflowJobsResponse = await fetch(
                  `https://circleci.com/api/v2/workflow/${workflow.id}/job?circle-token=${circleCiToken}`
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

    setWorkflowIdsWithActiveDeployment(workflowIds);
  };

  useEffect(() => {
    getWorkflowIdsWithActiveDeployment();
  }, []);

  return (
    <div>
      {errorMessage ? (
        <p>Encountered error: &quot{errorMessage}&quot</p>
      ) : (
        <div>
          <Workflows workflowIds={workflowIdsWithActiveDeployment} />
        </div>
      )}
    </div>
  );
};

export default Main;
