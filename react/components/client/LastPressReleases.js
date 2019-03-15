import React from 'react'

const LastPressReleases = (props) => {

    // Example from result widget
    var arr = Object.values(props.items);

    const Items = arr.map((item, i) => {
        return (
            <li key={i}>
                <a href={item.href}><span className="press_date">{item.date}</span></a>
                <span className="press_desc">{item.description}</span>
            </li>
        )
    });

    return(
        <div className="pressRelease">
            <ul className="press_release_list">
                {Items}
            </ul>
        </div>
    )
}

export default LastPressReleases
