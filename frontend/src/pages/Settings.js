import React from 'react';

import Typography from '@material-ui/core/Typography';

import useStyles from './dashboard/Dashboard.styles';

import logoWithBg from '/images/logo-with-bg.svg';

const Settings = () => {
  const classes = useStyles();

  return (
    <div className={classes.root}>
      <Typography variant="h3">Settings Page</Typography>
      <br />
      <img src={logoWithBg} alt="logo" width="30%" height="auto" />
    </div>
  );
};

export default Settings;
