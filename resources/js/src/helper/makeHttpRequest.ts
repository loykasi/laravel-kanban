import { appBase } from "../app/appBase";
import { getUserData } from "./getUserData";


type HttpVerbType = 'GET'|'POST'|'PUT'|'DELETE';

export function makeHttpRequest<TInput, TResponse>(endpoint: string, verb: HttpVerbType, input?: TInput) {
    return new Promise<TResponse>(async(resolve, reject) => {
        try {
            const userData = getUserData();
            const res = await fetch(
                `${appBase.apiBaseURL}/${endpoint}`,
                {
                    method: verb,
                    headers: {
                        "Accept": "application/json",
                        "content-type": "application/json",
                        "Authorization": "Bearer " + userData?.token,
                        "X-Socket-ID": window.Echo.socketId()
                    },
                    body: JSON.stringify(input)
                });

            const text = await res.text();
            const data: TResponse = JSON.parse(text);
            if (!res.ok) {
                reject(data);
            }
            resolve(data);
        } catch (error) {
            console.log('error: ' + error);
            reject(error);
        }
    })
}