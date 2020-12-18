import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import PropTypes from 'prop-types';
import styled from 'styled-components';

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

  const Form = styled.form`
    margin-bottom: 40px;
  `;

  const TokenInput = styled.input`
    height: 20px;
    width: 280px;
  `;

  const SubmitInput = styled.input`
    background-color: #4ca8e0;
    color: white;
    border: none;
    height: 30px;
    width: 60px;
    border-radius: 10px;
    margin-left: 10px;
    cursor: pointer;

    &:hover {
      font-weight: bold;
    }
  `;

  const Paragraphe = styled.p`
    margin: 10px 0;
  `;

  const Url = styled.a`
    color: #4ca8e0;
  `;

  const WarningParagraphe = styled.p`
    margin-top: 40px;
    font-weight: bold;
  `;

  return (
    <div className="circle-ci-token-form">
      <Form onSubmit={handleSubmit(onSubmit)}>
        <TokenInput name="token" type="text" ref={register({ required: true })} />
        {errors.exampleRequired && <span>This field is required</span>}
        <SubmitInput type="submit" value="Save" />
      </Form>
      <Paragraphe>Please enter your CircleCI token to access the application.</Paragraphe>
      <Paragraphe>
        Please follow <Url href={url}>these instructions</Url> to generate your personal CircleCI token and copy it
        here.
      </Paragraphe>
      <WarningParagraphe>Warning!</WarningParagraphe>
      <Paragraphe>
        You can only use this application if you have access to the Onboader repository on Github.
      </Paragraphe>
    </div>
  );
};

CircleCiTokenForm.propTypes = {
  state: PropTypes.object,
};

export default CircleCiTokenForm;
