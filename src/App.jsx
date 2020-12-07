import React, { useEffect, useState } from 'react';

const App = () => {
  const [workflows, setWorkflows] = useState([]);
  const [errorMessage, setErrorMessage] = useState('');

  const getWorkflowsWithActiveDeployment = async () => {
    const pipelinesResponse = await fetch(
      'https://circleci.com/api/v2/project/gh/akeneo/onboarder/pipeline?circle-token=9c86222bd6eea4f14fe22ec4f179e0ea8c0d7efd'
    );
    const workflowIds = [];
    pipelinesResponse
      .json()
      .then((result) =>
        result.items.map(async (pipeline) => {
          const pipelineWorkflowsResponse = await fetch(
            `https://circleci.com/api/v2/pipeline/${pipeline.id}/workflow?circle-token=9c86222bd6eea4f14fe22ec4f179e0ea8c0d7efd`
          );

          pipelineWorkflowsResponse
            .json()
            .then((result) => {
              result.items.map((workflow) => {
                workflowIds.push(workflow.id);
              });

              setWorkflows(JSON.stringify(workflowIds));
            })
            .catch((error) => setErrorMessage(error.message));
        })
      )
      .catch((error) => setErrorMessage(error.message));
  };

  useEffect(() => {
    getWorkflowsWithActiveDeployment();
  }, []);

  return (
    <div>
      {errorMessage ? (
        <p>Encountered error: &quot{errorMessage}&quot</p>
      ) : (
        <div>
          <p>{workflows}</p>
        </div>
      )}
    </div>
  );
};

export default App;
