/**
 * Created by hebidu on 16/6/14.
 */
'use strict';
var PagerLink = React.createClass({

    clickEvent:function(){
        if(this.props.className.indexOf('disabled') < 0 && this.props.className.indexOf('active') < 0){
            this.props.callBack(this.props.index);
        }
    },
    render: function() {
        return (<li  onClick={this.clickEvent} className={this.props.className} >
                <a href="javascript:void(0)">{this.props.text}</a>
            </li>);
    }
});

var Pager = React.createClass({
    getInitialState: function() {
        return {
            goIndex:''
        };
    },
    getDefaultProps: function() {
        return {
            totalCount:0,
            firstText:'First',
            prevText:'Prev',
            nextText:'Next',
            lastText:'Last',
            showLinkNum:10 ,//如果设置小于0的数字，那么则不显示数字标签
            alwaysShow:true,//当总页数只有一页时是否显示
            goWidth:50,//跳转输入框的宽度
            recordTextFormat: '{0}/{1}' //{0}对应当前页 {1}对应总页数 {2}对应总记录数 如果不希望显示此部分内容，将此部分赋空值
        };
    },

    callBack:function(index){
        this.props.callBack(index);
    },
    getPageLink: function(p){
        return <PagerLink key={p.Key} text={p.Text} index={p.Index} className={p.ClassName} callBack={this.callBack}/>;
    },
    goIndexChanged:function(e){
        var n = parseInt(e.target.value);
        var v='';
        if(!isNaN(n) && n > 0){
            v= Math.min(n,this.getTotalPages())+'';
        }

        this.setState({goIndex:v});
    },
    getTotalPages:function(){
        return Math.ceil(this.props.totalCount / this.props.pageSize);
    },
    goClicked:function(){
        var idx = parseInt(this.state.goIndex);

        if(idx >= 0 && idx != this.props.pageIndex ){
            this.callBack(idx);
            this.setState({goIndex:''});
        }
    },

    render: function() {
        var display='';
        if(!this.props.alwaysShow || this.props.totalCount == 0){
            display = this.props.totalCount<=this.props.pageSize?'none':'';
        }
        var totalPages = this.getTotalPages();
        var arr=[];
        var prevDisplay = 1 == this.props.pageIndex?'disabled':'';
        var lastDisplay = totalPages == this.props.pageIndex?'disabled':'';

        arr.push(
            this.getPageLink({
                Key : "F",
                Text :  this.props.firstText,
                Index : 1,
                ClassName : prevDisplay
            })
        );
        arr.push(
            this.getPageLink({
                Key : "P",
                Text :  this.props.prevText,
                Index : Math.max(this.props.pageIndex-1,0),
                ClassName : prevDisplay
            })
        );

        if(this.props.showLinkNum > 0){

            //PageIndex从1开始计算  1 2  3 4
            var startIndex = 1;
            if(this.props.pageIndex > this.props.showLinkNum){
                startIndex = this.props.pageIndex - (this.props.pageIndex % this.props.showLinkNum);
                startIndex = Math.min(startIndex,totalPages-this.props.showLinkNum+1);
            }

            var endIndex = Math.min(startIndex + this.props.showLinkNum,totalPages);
            for(var i=0;i<this.props.showLinkNum && startIndex+i <= totalPages;i++){
                arr.push(
                    this.getPageLink({
                        Key : startIndex + i,
                        Text :  startIndex + i ,
                        Index : startIndex + i,
                        ClassName : (startIndex + i)==this.props.pageIndex?'active':''
                    })
                );
            }
        }
        arr.push(
            this.getPageLink({
                Key : "N",
                Text :  this.props.nextText,
                Index : Math.min(this.props.pageIndex+1,totalPages),
                ClassName : lastDisplay
            })
        );
        arr.push(
            this.getPageLink({
                Key : "L",
                Text :  this.props.lastText,
                Index : totalPages ,
                ClassName : lastDisplay
            })
        );
        if(totalPages > this.props.showLinkNum){//显示快速跳转输入框
        arr.push(
                <li key="G">
                <div className="input-group" style={{display:'inline-block',float:'left'}}>
                <input type="text" className="form-control" maxLength={(totalPages+"").length} value={this.state.goIndex} onChange={this.goIndexChanged} style={{width:this.props.goWidth}} />
                <span className="input-group-btn" style={{display:'inline-block'}}>
                <button className="btn btn-default" onClick={this.goClicked} type="button">Go</button>
                </span>
                </div>
                </li>
                );
        }

        if(this.props.recordTextFormat.length>0){//显示文本
            arr.push(
                <li key="T" style={{marginLeft:5}}>
                <span>{this.props.recordTextFormat.replace(/\{0\}/g, this.props.pageIndex)
                .replace(/\{1\}/g, totalPages).replace(/\{2\}/g, this.props.totalCount)}</span>
                </li>
            );
        }

        return (
            <ul className="pagination" style={{margin: '0px 0px',display:display}}>{arr}</ul>

        );
    }
});
window.Pager = Pager;
//END Pager component