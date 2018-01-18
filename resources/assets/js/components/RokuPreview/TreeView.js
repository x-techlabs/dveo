import React from 'react';
import ReactDOM from 'react-dom';

import Carousel from 'slick-carousel';
import Slider from 'react-slick';

export default class TreeView extends React.Component {
	
	constructor(props){
		super(props);
	}

	componentDidUpdate() {
		var shelfSettings = {
			accessibility: true,
			pauseOnHover: true,

			dots: false,
			arrows: false,
  			lazyLoad: 'ondemand',
			infinite: true,
			speed: 500,
			slidesToShow: (this.props.shelf=="TopShelf") ? 1 : 3 ,
		}
		$('.'+this.props.shelf).slick(shelfSettings);
	}

	render() {
		var styles = {
			width: "100%",
			height: (this.props.shelf=="TopShelf"?"300px":"100px")
		}
		return (
			<div>
				<div className={this.props.shelf}>
					{
						this.props.playlists.map((item, index)=>{
							return (
								<div key={index} style={{padding: "0 10px"}}>
									<img data-lazy={item['thumbnail_name']} style={styles}/>
									{this.props.shelf=="LowShelf" ? item['title'] : ""}
								</div>
							);
						})
					}
				</div>
			</div>
		);
	}
}