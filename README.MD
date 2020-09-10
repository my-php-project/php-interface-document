# 自动扫描PHP注释行解析接口文档
- 支持自定义框架 (支持TP5)，可扩展
- 支持自定义注释解析（内置JetbrainsIDE解析）
- 支持导出自定义格式（内置swagger2 + postman collectionV2）
- 支持自定义页面显示（暂未完成）
- 支持扫描任意目录


# 注解介绍
```
/**
 * 测试1
 * @param int $test 11111
 * @param int $test2 11111
 * hhhhhhhhhhhhhh233
 * 123123213223213
 * @param int $test3 11111
 * @param $test3 Index 123333
 * @return int code 1
*/
function method(){}
```
## url 接口地址
> @url 如果不存在，则自动根据获取

## method 接口方式
> @method GET/POST/... 

## title 接口名称
> 自动识别第一行 / @title注解

## param 参数 支持多个参数
> - @param 参数类型 变量名称 变量描述
> - @param 变量名称 参数类型 变量描述

## query 参数 支持多个参数  地址中的请求参数
> @query 变量名称 变量描述

## return 返回提示
> @return 描述

## weight 排序  默认100 从小到大
> @weight 100

# 截图
![wGQ7As.png](https://s1.ax1x.com/2020/09/10/wGQ7As.png)
