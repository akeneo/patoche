import React, { useEffect, useState } from 'react';
import Workflows from './Worflows';

const Main = () => {
  const [workflowIdsWithActiveDeployment, setWorkflowIdsWithActiveDeployment] = useState([]);
  const [errorMessage, setErrorMessage] = useState('');
  const workflowIds = [];

  const getWorkflowIdsWithActiveDeployment = async () => {
    const pipelinesResponse = await fetch(
      'https://circleci.com/api/v2/project/gh/akeneo/onboarder/pipeline?circle-token=9c86222bd6eea4f14fe22ec4f179e0ea8c0d7efd'
    );
    await pipelinesResponse
      .json()
      .then(async (pipelines) => {
        for (const pipeline of pipelines.items) {
          const pipelineWorkflowsResponse = await fetch(
            `https://circleci.com/api/v2/pipeline/${pipeline.id}/workflow?circle-token=9c86222bd6eea4f14fe22ec4f179e0ea8c0d7efd`
          );

          pipelineWorkflowsResponse
            .json()
            .then(async (workflows) => {
              for (const workflow of workflows.items) {
                const workflowJobsResponse = await fetch(
                  `https://circleci.com/api/v2/workflow/${workflow.id}/job?circle-token=9c86222bd6eea4f14fe22ec4f179e0ea8c0d7efd`
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
