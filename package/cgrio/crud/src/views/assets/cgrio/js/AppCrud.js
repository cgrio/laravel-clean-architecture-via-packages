import React from 'react';
import ReactDOM from 'react-dom';
import Crud from './components/Crud';

export default class AppCrud extends React.Component {

    render() {
        return (<div className="">
                         <Crud >

                         </Crud>
                    </div>
        );
    }
}



if (document.getElementById('crud')) {
    ReactDOM.render(<AppCrud />, document.getElementById('crud'));
}
