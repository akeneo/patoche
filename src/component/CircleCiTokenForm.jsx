import React from 'react';
import { useForm } from 'react-hook-form';

const CircleCiTokenForm = () => {
  const { register, handleSubmit, errors } = useForm();
  const onSubmit = (data) => {
    localStorage.setItem('circle-token', data.token);
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <input name="token" type="text" ref={register({ required: true })} />
      {errors.exampleRequired && <span>This field is required</span>}
      <input type="submit" value="Save" />
    </form>
  );
};

export default CircleCiTokenForm;
