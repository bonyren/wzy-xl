<link rel="stylesheet" type="text/css" href="/static/js/easyui/themes/mobile.css?<?=STATIC_VER?>">
<script type="text/javascript" src="/static/js/easyui/jquery.easyui.mobile.js?<?=STATIC_VER?>"></script>
<style>
    *, ::after, ::before {box-sizing: border-box;}
    #mm{width:100%;height:100%;position:fixed;top:0;left:0;z-index:999;opacity:0;}
    #m-left-menu{
        width:180px;
        height:100%;
        border-right:1px solid #95B8E7;
        background-color: #ffffff;
    }
    #m-menu-mask{z-index:998;position:fixed;display:none;}
    .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        border-radius: .25rem;
        line-height: 1.8rem;
        margin: 5px;
        padding: 0;
        background: #ecf0f5;
    }
    .breadcrumb-item+.breadcrumb-item::before {
        display: inline-block;
        padding: 0 0.5rem;
        color: #6c757d;
        content: ">";
    }
</style>