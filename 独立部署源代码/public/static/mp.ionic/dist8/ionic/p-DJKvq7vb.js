/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import{a as o,w as s}from"./p-4DxY6_gG.js";import{f as t,s as r}from"./p-BhNEp2QP.js";import{c as a}from"./p-C-Cct-6D.js";const n=()=>{const n=window;n.addEventListener("statusTap",(()=>{o((()=>{const o=document.elementFromPoint(n.innerWidth/2,n.innerHeight/2);if(!o)return;const p=t(o);p&&new Promise((o=>a(p,o))).then((()=>{s((async()=>{p.style.setProperty("--overflow","hidden"),await r(p,300),p.style.removeProperty("--overflow")}))}))}))}))};export{n as startStatusTap}