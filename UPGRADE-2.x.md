# UPGRADE 2.x

UPGRADE FROM 1.x to 2.x
=======================

In process!!

TODO

- openspout 3.x 不能设置水平居中(4.x可以): https://github.com/openspout/openspout/pull/63
- openspout 目前不能单独设置列宽和行高: https://github.com/openspout/openspout/pull/77

## `box/spout` migrate to `openspout/openspout`

Replace `Box\Spout` with `OpenSpout` in your code

## `symfony/http-foundation` optional

if you use `browserDownload()`, require `symfony/http-foundation` first

## `SpoutExtendInterface` change

- Mark `buildCellStyle` and `buildRowStyle` deprecated, use `afterCellCreate` and `afterRowCreate` replace
