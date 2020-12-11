import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import PropTypes from 'prop-types';

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

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <input name="token" type="text" ref={register({ required: true })} />
      {errors.exampleRequired && <span>This field is required</span>}
      <input type="submit" value="Save" />
    </form>
  );
};

CircleCiTokenForm.propTypes = {
  state: PropTypes.objet,
};

export default CircleCiTokenForm;
