import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import PropTypes from 'prop-types';
import './CircleCiTokenForm.css';

const CircleCiTokenForm = (props) => {
  const { circleToken: setCircleToken } = {
    circleToken: useState(''),
    ...props.state,
  };

  const { register, handleSubmit, errors } = useForm();
  const onSubmit = (data) => {
    localStorage.setItem('circle-token', data.token);
    setCircleToken(data.token);
  };

  const url = `https://app.circleci.com/settings/user/tokens`;

  return (
    <div className="circle-ci-token-form">
      <form onSubmit={handleSubmit(onSubmit)}>
        <input className="token" name="token" type="text" ref={register({ required: true })} />
        {errors.exampleRequired && <span>This field is required</span>}
        <input className="submit" type="submit" value="Save" />
      </form>
      <p>Please enter your CircleCI token to access the application.</p>
      <p>
        Please follow <a href={url}>these instructions</a> to generate your personal CircleCI token and copy it here.
      </p>
      <p className="warning">Warning!</p>
      <p>You can only use this application if you have access to the Onboader repository on Github.</p>
    </div>
  );
};

CircleCiTokenForm.propTypes = {
  state: PropTypes.object,
};

export default CircleCiTokenForm;
