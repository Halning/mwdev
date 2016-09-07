$(document).ready(function(){
		$(".open_calc, .icon_calc").click(function(){
			$(".calc").slideToggle();
		});
		$(".icon_red").click(function(){
			$(".calc").slideUp();
		});

			$(".mousee")
			.mousedown(function(){
				//$(this).css({'cursor':'move'});
				$(".calc").draggable({
					scroll: true, 
					scrollSpeed: 500,
					containment: "window",
					cursor: 'move',
					snap: ".main-width"
				});
			})
			.mouseup(function(){
				$(this).css({'cursor':'pointer'});
				$(".calc").droppable({});
			});

			var f=0;
			$(".but_calc td").click(function(){
				var rel=$(this).attr("rel");
				var ww=$(".read_calc").text();
				var l=ww.length;

				
				if(ww=="0"){$(".read_calc").text("");}

				if(rel=="C"){
					save_num=0;
					$(".read_calc").text("0");
					return false;
				}

				if(rel=="CE"){
					var a=ww.substr(0,l-1);
					$(".read_calc").text(a);

					if(l==1){
						$(".read_calc").text("0");
					}
					return false;
				}

				if(rel=="."){
					
					//alert(ww[l-1]);
					if(ww[l-1]==".")
						return false;
					if(ww=="0"){
						$(".read_calc").text("0.");
						return false;
					}
					
				}

				if(rel=="+"){
					f=0;
					if(ww[l-1]=="+")
						return false;
				}

				if(rel=="-"){
					f=0;
					if(ww[l-1]=="-")
						return false;
				}

				if(rel=="*"){
					f=0;
					if(ww[l-1]=="*")
						return false;
				}

				if(rel=="/"){
					f=0;
					if(ww[l-1]=="/")
						return false;
				}

				if(f==1){
					$(".read_calc").text("");
					f=0;
				}

				if(rel=="="){
					f=1;
					var d=eval(ww);
					if(d % 1 !== 0){ // is_float 
					 	d=d.toFixed(3);
					}
					$(".read_calc").text(d);
					return false;
				}

				$(".read_calc").append(rel);
			});
		});