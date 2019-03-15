
import 'whatwg-fetch';

import { createElement } from 'react';
import ReactDom from 'react-dom';

import { BrochureBuilder } from './components/brochure-builder';

window.renderBrochureBundle = (
	{ google, FileManager, fileManagerPath },
	el,
	props, offices,
	{ cancelPath, resultPath, savePath, requestPath } = { cancelPath: '../', resultPath: '../', savePath: '', requestPath: '' }
) => {
	BrochureBuilder.google = google;
	BrochureBuilder.FileManager = FileManager;
	BrochureBuilder.fileManagerPath = fileManagerPath;
	
	ReactDom.render(
		createElement(BrochureBuilder, { props, offices, cancelPath, resultPath, savePath, requestPath }),
		el
	);
}
