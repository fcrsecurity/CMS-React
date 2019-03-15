
export const assign = (a, b = {}) => Object.assign({}, a, b);

export const toLocaleString = (value, prefix = '') => prefix + parseInt(value, 10).toLocaleString('en-US');

export const delay = (time) => new Promise((resolve) => setTimeout(resolve, time));

export const noop = (...args) => {}; // eslint-disable-line no-unused-vars

export const addr = (...args) => args.filter((item) => !!item);
